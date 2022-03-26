<?php

namespace App\Repositories;

use Exception;
use App\Models\VesselForm;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\VesselFormRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentVesselFormRepository.
 */
class EloquentVesselFormRepository extends EloquentBaseRepository implements VesselFormRepository
{
    /**
     * EloquentVesselFormRepository constructor.
     *
     * @param VesselForm $vessel_form
     */
    public function __construct(VesselForm $vessel_form)
    {
        parent::__construct($vessel_form);
    }

//    /**
//     * @param $name
//     *
//     * @return VesselForm
//     */
//    public function find($name)
//    {
//        /** @var VesselForm $vessel_form */
//        return false;//$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\VesselForm
     */
    public function store(array $input)
    {
        /** @var VesselForm $vessel_form */
        $input['doc_template_id'] = $input['doc_template']['id'];
        $vessel_form = $this->make(array_except($input, []));

//        if ($this->find($vessel_form->name)) {
//            throw new GeneralException(__('exceptions.backend.vessel_forms.already_exist'));
//        }

        DB::transaction(function () use ( $vessel_form, $input) {
            if (!$vessel_form->save()) {
                throw new GeneralException(__('exceptions.backend.vessel_forms.create'));
            }

            if (isset($input['form'])) {
                $vessel_form->addMedia($input['form'])->toMediaCollection('form');
            }

            return true;
        });

        return $vessel_form;
    }

    /**
     * @param VesselForm $vessel_form
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\VesselForm
     */
    public function update(VesselForm $vessel_form, array $input)
    {
//        if (($existingVesselForm = $this->find($vessel_form->name))
//          && $existingVesselForm->id !== $vessel_form->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.vessel_forms.already_exist'));
//        }
        $input['doc_template_id'] = $input['doc_template']['id'];

        DB::transaction(function () use ( $vessel_form, $input) {
            if (!$vessel_form->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.vessel_forms.update'));
            }

            if (isset($input['form'])) {
                if ($vessel_form->getMedia('form')->first()) {
                    $vessel_form->deleteMedia($vessel_form->getMedia('form')->first());
                }

                $vessel_form->addMedia($input['form'])->toMediaCollection('form');
            }

            return true;
        });

        return $vessel_form;
    }

    /**
     * @param VesselForm $vessel_form
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(VesselForm $vessel_form)
    {
        if (! $vessel_form->delete()) {
            throw new GeneralException(__('exceptions.backend.vessel_forms.delete'));
        }

        return true;
    }
}
