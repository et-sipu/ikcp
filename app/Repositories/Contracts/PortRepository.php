<?php

namespace App\Repositories\Contracts;

use App\Models\Port;

/**
 * Interface PortRepository.
 */
interface PortRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Port
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Port  $port
     * @param array $input
     *
     * @return mixed
     */
    public function update(Port $port, array $input);

    /**
     * @param Port $port
     *
     * @return mixed
     */
    public function destroy(Port $port);
}
