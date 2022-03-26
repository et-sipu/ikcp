<?php

namespace App\Repositories;

use Exception;
use App\Models\Port;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\PortRepository;

/**
 * Class EloquentPortRepository.
 */
class EloquentPortRepository extends EloquentBaseRepository implements PortRepository
{
    /**
     * EloquentPortRepository constructor.
     *
     * @param Port $port
     */
    public function __construct(Port $port)
    {
        parent::__construct($port);
    }

    /**
     * @param $name
     *
     * @return Port
     */
    public function find($name)
    {
        /** @var Port $port */
        return $this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Port
     */
    public function store(array $input)
    {
        /** @var Port $port */
        $port = $this->make($input);

        if ($this->find($port->name)) {
            throw new GeneralException(__('exceptions.backend.vessels.already_exist'));
        }

        if (! $port->save()) {
            throw new GeneralException(__('exceptions.backend.vessels.create'));
        }

        return $port;
    }

    /**
     * @param Port  $port
     * @param array $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Port
     */
    public function update(Port $port, array $input)
    {
        if (($existingPort = $this->find($port->name))
          && $existingPort->id !== $port->id
        ) {
            throw new GeneralException(__('exceptions.backend.vessels.already_exist'));
        }

        if (! $port->update($input)) {
            throw new GeneralException(__('exceptions.backend.vessels.update'));
        }

        return $port;
    }

    /**
     * @param Port $port
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Port $port)
    {
        if (! $port->delete()) {
            throw new GeneralException(__('exceptions.backend.vessels.delete'));
        }

        return true;
    }
}
