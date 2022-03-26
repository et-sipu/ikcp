<?php

namespace App\Events;

use App\Models\Vessel;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Queue\SerializesModels;

class IMOReportGenerated
{
    use SerializesModels;
    /**
     * @var Vessel
     */
    public $vessel;
    /**
     * @var Media
     */
    public $media;

    /**
     * IMOReportGenerated constructor.
     *
     * @param Vessel $vessel
     * @param Media  $media
     */
    public function __construct(Vessel $vessel, Media $media)
    {
        $this->vessel = $vessel;
        $this->media = $media;
    }
}
