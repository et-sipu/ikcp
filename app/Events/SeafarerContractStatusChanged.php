<?php

namespace App\Events;

use App\Models\SeafarerContract;
use Illuminate\Queue\SerializesModels;

class SeafarerContractStatusChanged
{
    use SerializesModels;
    /**
     * @var SeafarerContract
     */
    public $seafarerContract;

    public $new_status;

    /**
     * @param SeafarerContract $seafarerContract
     * @param $new_status
     */
    public function __construct(SeafarerContract $seafarerContract, $new_status)
    {
        $this->seafarerContract = $seafarerContract;
        $this->new_status = $new_status;
    }
}
