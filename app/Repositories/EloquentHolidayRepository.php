<?php

namespace App\Repositories;

use Exception;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\HolidayRepository;

/**
 * Class EloquentHolidayRepository.
 */
class EloquentHolidayRepository extends EloquentBaseRepository implements HolidayRepository
{
    /**
     * EloquentHolidayRepository constructor.
     *
     * @param Holiday $holiday
     */
    public function __construct(Holiday $holiday)
    {
        parent::__construct($holiday);
    }

//    /**
//     * @param $name
//     *
//     * @return Holiday
//     */
//    public function find($name)
//    {
//        /** @var Holiday $holiday */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Holiday
     */
    public function store(array $input)
    {
        /** @var Holiday $holiday */
        $holiday = $this->make(array_except($input, []));

//        if ($this->find($holiday->name)) {
//            throw new GeneralException(__('exceptions.backend.holidays.already_exist'));
//        }

        DB::transaction(function () use ($holiday) {
            if (! $holiday->save()) {
                throw new GeneralException(__('exceptions.backend.holidays.create'));
            }

            return true;
        });

        return $holiday;
    }

    /**
     * @param Holiday $holiday
     * @param array   $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Holiday
     */
    public function update(Holiday $holiday, array $input)
    {
//        if (($existingHoliday = $this->find($holiday->name))
//          && $existingHoliday->id !== $holiday->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.holidays.already_exist'));
//        }

        DB::transaction(function () use ($holiday, $input) {
            if (! $holiday->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.holidays.update'));
            }

            return true;
        });

        return $holiday;
    }

    /**
     * @param Holiday $holiday
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Holiday $holiday)
    {
        if (! $holiday->delete()) {
            throw new GeneralException(__('exceptions.backend.holidays.delete'));
        }

        return true;
    }
}
