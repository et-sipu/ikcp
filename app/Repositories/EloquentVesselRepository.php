<?php

namespace App\Repositories;

use Exception;
use App\Models\Vessel;
use App\Models\Document;
use App\Events\IMOReportGenerated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Exceptions\GeneralException;
use Intervention\Image\Facades\Image;
use App\Repositories\Contracts\VesselRepository;
use App\Repositories\Traits\SyncAttachmentsTrait;

/**
 * Class EloquentVesselRepository.
 */
class EloquentVesselRepository extends EloquentBaseRepository implements VesselRepository
{
    use SyncAttachmentsTrait;

    /**
     * EloquentVesselRepository constructor.
     *
     * @param Vessel $vessel
     */
    public function __construct(Vessel $vessel)
    {
        parent::__construct($vessel);
    }

    /**
     * @param $name
     *
     * @return Vessel
     */
    public function find($name)
    {
        /** @var Vessel $vessel */
        return $this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Vessel
     */
    public function store(array $input)
    {
        if (isset($input['certificates']) && \is_array($input['certificates']) && \count(array_unique(array_column($input['certificates'], 'type'))) !== \count($input['certificates'])) {
            throw new GeneralException(__('exceptions.backend.vessels.duplicate_certificates'));
        }

        $input['port_of_registry_id'] = $input['port_of_registry']['id'];
        $input['piloted_by'] = $input['pilot']['id'];

        /** @var Vessel $vessel */
        $vessel = $this->make(array_except($input, ['certificates', 'stamp', 'vessel_specs']));

        if ($this->find($vessel->name)) {
            throw new GeneralException(__('exceptions.backend.vessels.already_exist'));
        }

        DB::transaction(function () use ($vessel, $input) {
            if (! $vessel->save()) {
                throw new GeneralException(__('exceptions.backend.vessels.create'));
            }

            if (isset($input['stamp'])) {
                $vessel->addMedia($input['stamp'])->toMediaCollection('stamp');
            }
            if (isset($input['vessel_specs'])) {
                $vessel->addMedia($input['vessel_specs'])->toMediaCollection('vessel_specs');
            }

            $this->sync_certificates($vessel, $input['certificates'] ? $input['certificates'] : []);
            $this->sync_attachments($vessel, $input['port_clearances'] ? $input['port_clearances'] : [], 'port_clearances', ['departure_port', 'departure_date']);

            if (! $input['certificates_summaries']) {
                $input['certificates_summaries'] = [];
            }
            if (\count(array_unique(array_pluck(array_filter($input['certificates_summaries'], function ($item) {return $item['url'] || 0 === $item['id']; }), 'quarter_date')))
                !== \count(array_pluck(array_filter($input['certificates_summaries'], function ($item) {return $item['url'] || 0 === $item['id']; }), 'quarter_date'))) {
                throw new GeneralException(__('exceptions.backend.vessels.duplicate_certificate_summary'));
            }

            $this->sync_attachments($vessel, $input['certificates_summaries'] ? $input['certificates_summaries'] : [], 'certificates_summaries', ['quarter_date']);
            $this->sync_attachments($vessel, isset($input['GA_Plans']) && $input['GA_Plans'] ? $input['GA_Plans'] : [], 'GA_Plans');

            return true;
        });

        return $vessel;
    }

    /**
     * @param Vessel $vessel
     * @param array  $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Vessel
     */
    public function update(Vessel $vessel, array $input)
    {
        if (isset($input['certificates']) && \is_array($input['certificates']) && \count(array_unique(array_column($input['certificates'], 'type'))) !== \count($input['certificates'])) {
            throw new GeneralException(__('exceptions.backend.vessels.duplicate_certificates'));
        }

        if (($existingVessel = $this->find($vessel->name))
          && $existingVessel->id !== $vessel->id
        ) {
            throw new GeneralException(__('exceptions.backend.vessels.already_exist'));
        }

        $input['port_of_registry_id'] = $input['port_of_registry']['id'];
        $input['piloted_by'] = $input['pilot']['id'];

        DB::transaction(function () use ($vessel, $input) {
            if (! $vessel->update(array_except($input, ['certificates', 'stamp', 'vessel_specs']))) {
                throw new GeneralException(__('exceptions.backend.vessels.update'));
            }

            if (isset($input['stamp'])) {
                if ($vessel->getMedia('stamp')->first()) {
                    $vessel->deleteMedia($vessel->getMedia('stamp')->first());
                }

                $vessel->addMedia($input['stamp'])->toMediaCollection('stamp');
            }
            if (isset($input['vessel_specs']) && $input['vessel_specs'] && $input['vessel_specs']->isValid()) {
                if ($vessel->getMedia('vessel_specs')->first()) {
                    $vessel->deleteMedia($vessel->getMedia('vessel_specs')->first());
                }

                $vessel->addMedia($input['vessel_specs'])->toMediaCollection('vessel_specs');
            }

            $this->sync_certificates($vessel, $input['certificates'] ? $input['certificates'] : []);

            $this->sync_attachments($vessel, $input['port_clearances'] ? $input['port_clearances'] : [], 'port_clearances', ['departure_port', 'departure_date']);

            if (! $input['certificates_summaries']) {
                $input['certificates_summaries'] = [];
            }
            if (\count(array_unique(array_pluck(array_filter($input['certificates_summaries'], function ($item) {return $item['url'] || 0 === $item['id']; }), 'quarter_date')))
                !== \count(array_pluck(array_filter($input['certificates_summaries'], function ($item) {return $item['url'] || 0 === $item['id']; }), 'quarter_date'))) {
                throw new GeneralException(__('exceptions.backend.vessels.duplicate_certificate_summary'));
            }

            $this->sync_attachments($vessel, $input['certificates_summaries'] ? $input['certificates_summaries'] : [], 'certificates_summaries', ['quarter_date']);
            $this->sync_attachments($vessel, isset($input['GA_Plans']) && $input['GA_Plans'] ? $input['GA_Plans'] : [], 'GA_Plans');

            return true;
        });

        return $vessel;
    }

    /**
     * @param Vessel $vessel
     * @param array  $certificates
     *
     * @throws \Throwable
     *
     * @return bool
     */
    protected function sync_certificates(Vessel $vessel, $certificates = [])
    {
        DB::transaction(function () use ($vessel, $certificates) {
            if (! $certificates) {
                $vessel->documents->each(function ($document) {
                    $document->delete();
                });

                return;
            }
            $ids_list = array_filter(array_column($certificates, 'id'));

            $vessel->documents()->whereNotIn('id', $ids_list)->get()->each(function ($document) {
                $document->delete();
            });

            foreach ($certificates as $certificate) {
//                if ((! isset($certificate['type']) || '' === $certificate['type']) || (! isset($certificate['expiry']) || '' === $certificate['expiry'])) {
                if ((! isset($certificate['type']) || '' === $certificate['type'])) {
                    continue;
                }

                $certificate_type = $certificate['type'];

                if (isset($certificate['id']) && 0 !== $certificate['id']) {
                    $vesselCertificate = Document::find($certificate['id']);
                    if (! $vesselCertificate) {
                        continue;
                    }
                    $vesselCertificate->document_no = $certificate['number'];
                    $vesselCertificate->document_expiry_date = $certificate['expiry'];
                    $vesselCertificate->remarks = $certificate['remarks'];
                    $vesselCertificate->document_type = $certificate_type;
                    $vesselCertificate->save();
                } else {
                    $vesselCertificate = new Document();
                    $vesselCertificate->document_no = $certificate['number'];
                    $vesselCertificate->document_expiry_date = $certificate['expiry'];
                    $vesselCertificate->remarks = $certificate['remarks'];
                    $vesselCertificate->document_type = $certificate_type;
                    $vessel->documents()->save($vesselCertificate);
                }

                if (isset($certificate['file']) && $certificate['file']) {
                    if ($vessel->getMedia($vesselCertificate->document_type)->first()) {
                        $vessel->deleteMedia($vessel->getMedia($vesselCertificate->document_type)->first());
                    }

                    $vessel->addMedia($certificate['file'])->toMediaCollection($vesselCertificate->document_type);
                }
            }

            return true;
        });

        return true;
    }

    /**
     * @param Vessel $vessel
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Vessel $vessel)
    {
        if (! $vessel->delete()) {
            throw new GeneralException(__('exceptions.backend.vessels.delete'));
        }

        return true;
    }

    public function process_imo_report(Vessel $vessel, array $input)
    {
        $pdf = App::make('dompdf.wrapper');

        $vessel->load(['onboard_seafarers']);
        $onboard_seafarers = $vessel->onboard_seafarers()->getQuery()
            ->select(['ranks.name as rank', DB::raw('concat(employees.name,\' \',surname) as full_name'), 'employees.*'])
            ->leftJoin('employee_capabilities_infos', 'employee_id', '=', 'employees.id')
            ->leftJoin('ranks', 'employee_capabilities_infos.rank_id', '=', 'ranks.id')
            ->orderBy('ranks.department')
            ->orderBy('ranks.order')
            ->get();

        $movement_type = $input['movement_type'];
        $goal_port = $input['goal_port'];
        $last_port = $input['last_port'];
        $movement_date = $input['movement_date'];

        $signature = null;
        if ($input['generate']) {
            $vessel_stamp = $vessel->getMedia('stamp')->first() ? $vessel->getMedia('stamp')->first()->getPath() : '';
            $master_signature = $vessel->current_master() && $vessel->current_master()->getMedia('seafarer_signature')->first() ? $vessel->current_master()->getMedia('seafarer_signature')->first()->getPath() : '';

            $signature = Image::canvas(200, 150);
            if ($vessel_stamp) {
                $signature->insert(Image::make($vessel_stamp)->resize(200, 150));
            }
            if ($master_signature) {
                $signature->insert(Image::make($master_signature)->resize(160, 120)->opacity(100), 'center');
            }
        }

        $pdf->loadView('templates.imo', compact('vessel', 'goal_port', 'last_port', 'onboard_seafarers', 'movement_type', 'movement_date', 'signature'));
        if ($input['generate']) {
            $imo_report_filename = sprintf('storage/%s.pdf', $vessel->code.'_'.date('Ymd'));
            $pdf->save($imo_report_filename);
            $imo = $vessel->addMedia($imo_report_filename)->toMediaCollection('IMO');
            event(new IMOReportGenerated($vessel, $imo));
        }

        return $pdf;
    }
}
