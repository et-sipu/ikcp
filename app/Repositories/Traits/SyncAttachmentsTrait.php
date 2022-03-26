<?php

namespace App\Repositories\Traits;

use Spatie\MediaLibrary\HasMedia\HasMedia;

trait SyncAttachmentsTrait
{
    protected function sync_attachments(HasMedia $item, $files = [], $collection_name = 'attachments', $custom_properties = [], $preserve_files = false)
    {
        foreach ($files as $file) {
            $custom_data = array_only($file, $custom_properties);
            $file = array_except($file, $custom_properties);

            // There is a file uploaded so we have to choices either the file is updated or a new file provided
            if (isset($file['file']) && $file['file']) {
                // The file is updated so delete the old one
                if ($deleted = $item->getMedia($collection_name)->where('id', '=', $file['id'])->first()) {
                    $item->deleteMedia($deleted);
                }

                // Anyway add the uploaded file
                if(!$preserve_files)
                    $item->addMedia($file['file'])->withCustomProperties($custom_data)->toMediaCollection($collection_name);
                else
                    $item->addMedia($file['file'])
                        ->preservingOriginal()
                        ->withCustomProperties($custom_data)
                        ->toMediaCollection($collection_name);
            } // There is no file uploaded so maybe the information of custom properties is updated
            elseif ($custom_properties && $custom_data && $file['url']) {
                $media = $item->getMedia($collection_name)->where('id', '=', $file['id'])->first();
                foreach ($custom_data as $key => $custom_datum) {
                    $media->setCustomProperty($key, $custom_datum);
                    $media->save();
                }
            }

            if (0 !== $file['id'] && ! $file['url']) {
                if ($deleted = $item->getMedia($collection_name)->where('id', '=', $file['id'])->first()) {
                    $item->deleteMedia($deleted);
                }
            }
        }

        return true;
    }
}
