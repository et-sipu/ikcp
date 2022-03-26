<?php

namespace App\Repositories;

use Exception;
use App\Models\VesselInsurance;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\VesselInsuranceRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\Traits\SyncAttachmentsTrait;

/**
 * Class EloquentVesselInsuranceRepository.
 */
class EloquentVesselInsuranceRepository extends EloquentBaseRepository implements VesselInsuranceRepository
{
    use SyncAttachmentsTrait;

    /**
     * EloquentVesselInsuranceRepository constructor.
     *
     * @param VesselInsurance $vessel_insurance
     */
    public function __construct(VesselInsurance $vessel_insurance)
    {
        parent::__construct($vessel_insurance);
    }

    /**
     * @param $name
     *
     * @return VesselInsurance
     */
    public function find($name)
    {
        /* @var VesselInsurance $vessel_insurance */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\VesselInsurance
     */
    public function store(array $input)
    {
        /** @var VesselInsurance $vessel_insurance */
        $vessel_insurance = $this->make(array_except($input, []));

        if ($this->find($vessel_insurance->name)) {
            throw new GeneralException(__('exceptions.backend.vessel_insurances.already_exist'));
        }

        DB::transaction(function () use ( $vessel_insurance, $input) {
            if (!$vessel_insurance->save()) {
                throw new GeneralException(__('exceptions.backend.vessel_insurances.create'));
            }
            $this->sync_attachments($vessel_insurance, ($input['attachments']) ? $input['attachments'] : []);


            return true;
        });

        return $vessel_insurance;
    }

    /**
     * @param VesselInsurance $vessel_insurance
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\VesselInsurance
     */
    public function update(VesselInsurance $vessel_insurance, array $input)
    {
        if (($existingVesselInsurance = $this->find($vessel_insurance->name))
          && $existingVesselInsurance->id !== $vessel_insurance->id
        ) {
            throw new GeneralException(__('exceptions.backend.vessel_insurances.already_exist'));
        }
        DB::transaction(function () use ( $vessel_insurance, $input) {
            if (!$vessel_insurance->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.vessel_insurances.update'));
            }
            $this->sync_attachments($vessel_insurance, ($input['attachments']) ? $input['attachments'] : []);

            return true;
        });

        return $vessel_insurance;
    }

    /**
     * @param VesselInsurance $vessel_insurance
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(VesselInsurance $vessel_insurance)
    {
        if (! $vessel_insurance->delete()) {
            throw new GeneralException(__('exceptions.backend.vessel_insurances.delete'));
        }

        return true;
    }

}
