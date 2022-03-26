<?php

namespace App\Repositories;

use Exception;
use App\Models\Equipment;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\EquipmentRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentEquipmentRepository.
 */
class EloquentEquipmentRepository extends EloquentBaseRepository implements EquipmentRepository
{
    /**
     * EloquentEquipmentRepository constructor.
     *
     * @param Equipment $equipment
     */
    public function __construct(Equipment $equipment)
    {
        parent::__construct($equipment);
    }

    /**
     * @param $name
     *
     * @return Equipment
     */
    public function find($name)
    {
        /* @var Equipment $equipment */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Equipment
     */
    public function store(array $input)
    {
        //dd(array_values(implode(' ', array_values($input['brand']))));


        /** @var Equipment $equipment */
        $equipment = $this->make(array_except($input, []));
        $equipment->brand = $input['brand']['name'];
        if(auth()->user()->vessel){
            $equipment->vessel_id = auth()->user()->vessel->id;
        }
        else{
            $equipment->vessel_id = $input['vessel']['id'];
        }

        if ($this->find($equipment->name)) {
            throw new GeneralException(__('exceptions.backend.equipment.already_exist'));
        }

        DB::transaction(function () use ( $equipment, $input) {
            if (!$equipment->save()) {
                throw new GeneralException(__('exceptions.backend.equipment.create'));
            }

            return true;
        });

        return $equipment;
    }

    /**
     * @param Equipment $equipment
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Equipment
     */
    public function update(Equipment $equipment, array $input)
    {
        $input['brand'] = $input['brand']['name'];
        $input['vessel_id'] = $input['vessel']['id'];
       // dd($equipment->vessel_id);

        if (($existingEquipment = $this->find($equipment->name))
          && $existingEquipment->id !== $equipment->id
        ) {
            throw new GeneralException(__('exceptions.backend.equipment.already_exist'));
        }

        DB::transaction(function () use ( $equipment, $input) {

            if (!$equipment->update(array_except($input, []))) {

                throw new GeneralException(__('exceptions.backend.equipment.update'));
            }

            return true;
        });

        return $equipment;
    }

    /**
     * @param Equipment $equipment
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Equipment $equipment)
    {
        if (! $equipment->delete()) {
            throw new GeneralException(__('exceptions.backend.equipment.delete'));
        }

        return true;
    }
}
