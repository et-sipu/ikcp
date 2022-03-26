<?php

namespace App\Models\Traits;

trait GetAttachmentsTrait
{
    public function getAttachmentsInfoAttribute()
    {
        $attachments = [];

        $medias = $this->getMedia('attachments')->all();

        foreach ($medias as $media) {
            array_push($attachments,
                [
                    'id'   => isset($media) ? $media->id : 0,
                    'file' => null,
                    'url'  => isset($media) ? config('app.url').$media->getUrl() : null,
                    'path'  => isset($media) ? $media->getPath() : null,
                ]);
        }

        return $attachments;
    }
}
