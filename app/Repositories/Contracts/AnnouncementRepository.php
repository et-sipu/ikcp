<?php

namespace App\Repositories\Contracts;

use App\Models\Announcement;

/**
 * Interface AnnouncementlRepository.
 */
interface AnnouncementRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Announcement
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Announcement $announcement
     * @param array       $input
     *
     * @return mixed
     */
    public function update(Announcement $announcement, array $input);

    /**
     * @param Announcement $announcement
     *
     * @return mixed
     */
    public function destroy(Announcement $announcement);

    /**
     * @param Announcement $announcement
     * @return mixed
     */
    public function publish(Announcement $announcement);
}
