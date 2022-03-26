<?php
namespace App\Transformers;

use App\Models\Announcement;
use League\Fractal\TransformerAbstract;

class AnnouncementTransformer extends TransformerAbstract
{

    public function transform(Announcement $announcement)
    {
        return [
            'id' => $announcement->id,
			'subject' => $announcement->subject,
			'content' => $announcement->content,
			'destination' => $announcement->destination,
			'published_at' => $announcement->published_at ? $announcement->published_at->format('Y-m-d') : null,
            'attachments' => $announcement->attachments_info,
			'status' => $announcement->status,
            'can_edit' => $announcement->can_edit,
            'can_delete' => $announcement->can_delete,
        ];
    }
}