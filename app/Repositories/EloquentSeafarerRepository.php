<?php

namespace App\Repositories;

use App\Models\Seafarer;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\SeafarerRepository;

/**
 * Class EloquentSeafarerRepository.
 */
class EloquentSeafarerRepository extends EloquentEmployeeRepository implements SeafarerRepository
{
    /**
     * EloquentSeafarerRepository constructor.
     *
     * @param Seafarer $seafarer
     */
    public function __construct(Seafarer $seafarer)
    {
        parent::__construct($seafarer);
    }

    /**
     * @param Seafarer $seafarer
     * @param $comment
     *
     * @throws \Throwable
     *
     * @return bool|mixed
     */
    public function blacklist(Seafarer $seafarer, $comment)
    {
        $seafarer->blacklisted = now();

        DB::transaction(function () use ($seafarer, $comment) {
            $seafarer->save();

            auth()->user()->comment($seafarer, $comment);

            return true;
        });

        return true;
    }

    /**
     * @param Seafarer $seafarer
     * @param $comment
     *
     * @throws \Throwable
     *
     * @return bool|mixed
     */
    public function whitelist(Seafarer $seafarer, $comment)
    {
        $seafarer->blacklisted = null;

        DB::transaction(function () use ($seafarer, $comment) {
            $seafarer->save();

            auth()->user()->comment($seafarer, $comment);

            return true;
        });

        return true;
    }
}
