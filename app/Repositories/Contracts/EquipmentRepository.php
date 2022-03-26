<?php

namespace App\Repositories\Contracts;

use App\Models\Equipment;

/**
 * Interface EquipmentlRepository.
 */
interface EquipmentRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Equipment
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Equipment $equipment
     * @param array       $input
     *
     * @return mixed
     */
    public function update(Equipment $equipment, array $input);

    /**
     * @param Equipment $equipment
     *
     * @return mixed
     */
    public function destroy(Equipment $equipment);
}
