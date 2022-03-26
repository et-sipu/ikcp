<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\AttendanceRepresentative;
use App\Repositories\Contracts\AttendanceRepresentativeRepository;

/**
 * Class EloquentAttendanceRepresentativeRepository.
 */
class EloquentAttendanceRepresentativeRepository extends EloquentBaseRepository implements AttendanceRepresentativeRepository
{
    /**
     * EloquentAttendanceRepresentativeRepository constructor.
     *
     * @param AttendanceRepresentative $represent
     */
    public function __construct(AttendanceRepresentative $represent)
    {
        parent::__construct($represent);
    }

    /**
     * @param $name
     *
     * @return AttendanceRepresentative
     */
    public function find($user_id)
    {
        return $this->query()->where('user_id', $user_id)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\AttendanceRepresentative
     */
    public function store(array $input)
    {
        /** @var Branch $branch */
        $role = Role::where('name', 'Attendance Representative')->first();
        // dd($role->id);
        $represent = $this->make(array_except($input, []));
        $represent->user_id = $input['assigned_user']['id'];
        $represent->branch_id = $input['assigned_branch']['id'];

        $represent->departments = array_pluck($input['assigned_department'], 'id');

        if ($this->find($represent->user_id)) {
            throw new GeneralException(__('exceptions.backend.representative.already_exist'));
        }

        DB::transaction(function () use ($represent, $role) {
            if (! $represent->save()) {
                throw new GeneralException(__('exceptions.backend.representative.create'));
            }
            $represent->assigned_user->roles()->attach($role->id);

            return true;
        });

        return $represent;
    }

    public function update(AttendanceRepresentative $represent, array $input)
    {
        //dd($input);
        //  $represent = $this->make(array_except($input, []));
//        $represent->user_id = $input['assigned_user']['id'];
        $represent->branch_id = $input['assigned_branch']['id'];
//        $arr_length = count($input['assigned_department']);

        $represent->departments = array_pluck($input['assigned_department'], 'id');

        DB::transaction(function () use ($represent, $input) {
            if (! $represent->update($input)) {
                throw new GeneralException(__('exceptions.backend.representative.update'));
            }

            return true;
        });

        return $represent;
    }

    public function destroy(AttendanceRepresentative $represent)
    {
        $represent->assigned_user->roles()->detach(Role::where('name', 'Attendance Representative')->first()->id);
        if (! $represent->delete()) {
            throw new GeneralException(__('exceptions.backend.representative.delete'));
        }

        return true;
    }

    /*
     * @param Branch $branch
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Branch
     */
}
