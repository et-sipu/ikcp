<?php

namespace App\Http\Controllers\Backend;

use App\Models\Port;
use App\Models\Rank;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmploymentLevel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class AjaxController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function imageUpload(Request $request)
    {
        $uploadedImage = $request->file('upload');

        // Resize image below 600px width if needed
        $image = Image::make($uploadedImage->openFile())->widen(600, function ($constraint) {
            $constraint->upsize();
        });

        $imagePath = "/tmp/{$uploadedImage->getClientOriginalName()}";
        Storage::disk('public')->put($imagePath, $image->stream());

        return [
            'uploaded' => true,
            'url'      => "/storage{$imagePath}",
            'width'    => $image->width(),
            'height'   => $image->height(),
        ];
    }

    /**
     * @param $constant
     *
     * @return mixed
     */
    public function getListsOf($constant)
    {
        return Config::get('constants.'.$constant); //array_combine($data,$data) ;
    }

    /**
     * @return Port[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getListsOfPorts()
    {
        return Port::select(['name', 'id'])->get();
    }

    /**
     * @return Port[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getListsOfRanks()
    {
        $departments = Rank::where('department', '0')->with('ranks')->get();
        $ranks = [];
        foreach ($departments as $department) {
            $ranks[$department->name] = $department->ranks->map(function ($item) {
                return ['id' => $item->id, 'name' => $item->name];
            });
        }

        return $ranks;
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getListsOfMasters()
    {
        return User::select(['name', 'id'])->whereHas('roles', function ($query) {
            $query->where('name', '=', 'VM');
        })->get();
    }

    public function getListsOfUsers(Request $request)
    {
        $users = User::leftJoin('employees', 'users.id', '=', 'user_id')->select([DB::raw('IFNULL(concat(employees.name,\' \',surname), users.name) as name'), 'users.id'])->get();

        if($request->get('as_objects')){
            return $users->pluck('name','id')->toArray();
        }

        return $users;
    }

    public function getListsOfEmploymentLevels()
    {
        return EmploymentLevel::all();
    }

    public function getListsOfEmployees()
    {
        $employees = Employee::select(['name', 'surname', 'id'])->get();
        $employees = array_map(function ($employee) {
            return ['id' => $employee['id'], 'name' => ucwords($employee['full_name'])];
        }, $employees->toArray());

        return $employees;
    }

    public function getBusinessDaysCount($start_date, $end_date)
    {
        return days_count($start_date, $end_date);
//        $holidays =  Holiday::whereYear('start_date', '=', date('Y'))->select(['start_date','end_date'])->get();
//        return Carbon::createFromFormat('Y-m-d', $end_date)->addDay(1)->diffInDaysFiltered(function(Carbon $date) use($holidays){
//            $d = $date->format('Y-m-d');
////            return !($date->isWeekend() || Holiday::whereRaw('? between start_date and end_date', [$d])->first());
//            return !($date->isWeekend() || $holidays->where('start_date','<=',$d)->where('end_date','>=',$d)->count());
//        },Carbon::createFromFormat('Y-m-d', $start_date));
    }
}
