<?php

namespace App\Events;

use App\Models\SeafarerContract;
use Illuminate\Queue\SerializesModels;

class SeafarerContractCreated
{
    use SerializesModels;
    /**
     * @var SeafarerContract
     */
    public $seafarerContract;

    public $new_status;

    /**
     * @param SeafarerContract $seafarerContract
     */
    public function __construct(SeafarerContract $seafarerContract)
    {
        $this->seafarerContract = $seafarerContract;
    }
}
