<?php

namespace App\Repositories;

use App\Events\AnnouncementPublished;
use App\Mail\AnnouncementMail;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Traits\SyncAttachmentsTrait;
use Carbon\Carbon;
use Exception;
use App\Models\Announcement;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\AnnouncementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class EloquentAnnouncementRepository.
 */
class EloquentAnnouncementRepository extends EloquentBaseRepository implements AnnouncementRepository
{
    use SyncAttachmentsTrait;

    /**
     * EloquentAnnouncementRepository constructor.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        parent::__construct($announcement);
    }

//    /**
//     * @param $name
//     *
//     * @return Announcement
//     */
//    public function find($name)
//    {
//        /** @var Announcement $announcement */
//        return false;//$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Announcement
     */
    public function store(array $input)
    {
        /** @var Announcement $announcement */

        $announcement = $this->make(array_except($input, []));
        $announcement->status= 'DRAFT';

//        if ($this->find($announcement->name)) {
//            throw new GeneralException(__('exceptions.backend.announcements.already_exist'));
//        }

        DB::transaction(function () use ( $announcement, $input) {
            if (!$announcement->save()) {
                throw new GeneralException(__('exceptions.backend.announcements.create'));
            }

            $this->sync_attachments($announcement, $input['attachments'] ? $input['attachments'] : []);

            return true;
        });


        return $announcement;
    }

    /**
     * @param Announcement $announcement
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Announcement
     */
    public function update(Announcement $announcement, array $input)
    {
//        if (($existingAnnouncement = $this->find($announcement->name))
//          && $existingAnnouncement->id !== $announcement->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.announcements.already_exist'));
//        }

        DB::transaction(function () use ( $announcement, $input) {
            if (!$announcement->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.announcements.update'));
            }

            $this->sync_attachments($announcement, $input['attachments'] ? $input['attachments'] : []);

            return true;
        });

        return $announcement;
    }

    /**
     * @param Announcement $announcement
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Announcement $announcement)
    {
        if (! $announcement->delete()) {
            throw new GeneralException(__('exceptions.backend.announcements.delete'));
        }

        return true;
    }

    public function publish(Announcement $announcement)
    {
        if($announcement->update(['status' => 'PUBLISHED', 'published_at' => Carbon::now()->format('Y-m-d')])){
            throw new GeneralException(__('exceptions.backend.announcements.update'));
        }

        event(new AnnouncementPublished($announcement));

        return true;
    }
}
