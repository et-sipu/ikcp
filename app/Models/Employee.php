<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Models\Contracts\Commentable;
use App\Models\Scopes\IsEmployeeScope;
use App\Models\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Traits\HasOneThroughTrait;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Employee extends Model implements HasMedia, Auditable, Commentable
{
    use SoftDeletes, HasMediaTrait;
    use \OwenIt\Auditing\Auditable;
    use CommentableTrait;
    use HasOneThroughTrait;

    protected $fillable = [
        'name',
        'surname',
        'sex',
        'marital_status',
        'religion',
        'race',
        'nationality',
        'address',
        'nric_no',
        'date_of_join',
        'date_of_birth',
        'place_of_birth',
        'blood_type',
    ];

    protected static $scope = null;

    protected $hidden = [
        'is_seafarer',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['full_name'];

    // protected $appends = ['can_edit', 'can_delete'];

    public function __construct(array $attributes = [])
    {
        static::$scope = $this->getScope();
        parent::__construct($attributes);
    }

    public function getScope()
    {
        return new IsEmployeeScope();
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(static::$scope);

        self::deleting(function (self $employee) {
            $employee->contactInfo()->delete();
            $employee->financialInfo()->delete();
            $employee->jobInfo()->delete();
            $employee->spouseInfo()->delete();
            $employee->parentsInfo()->delete();
            $employee->documents()->delete();
            $employee->media()->delete();
        });
    }

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete employees');
    }

    public function getFullNameAttribute()
    {
        return ucwords($this->name.' '.$this->surname);
    }

    public function getRealNationalityAttribute()
    {
        return config('constants.nationalities.'.$this->nationality.'.nationality');
    }

    public function getNationalityObjAttribute()
    {
        return config('constants.nationalities.'.$this->nationality);
    }

    public function getPhotoPathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('photo')->first()) {
            return config('app.url').$media->getUrl();
        }

        return config('app.url').'/images/employee_default.png';
    }

    public function getServiceYearsAttribute()
    {
//        return $this->date_of_join ? Carbon::createFromFormat('Y-m-d', $this->date_of_join)->startOfDay()->diffInYears(Carbon::today()->startOfYear()) : 0;
        return $this->date_of_join ? Carbon::createFromFormat('Y-m-d', $this->date_of_join)->startOfDay()->diffInYears(Carbon::today()->startOfDay()) : 0;
    }

    public function getLatestContractingDateAttribute()
    {
        $join_date = $this->date_of_join ? Carbon::createFromFormat('Y-m-d', $this->date_of_join)->startOfDay() : Carbon::today()->startOfYear();

        $date = $join_date->year(date('Y'))->startOfDay();

        return $date > Carbon::today()
            ? $date->subYear()
            : $date;
    }

    public function getEndOfContractDateAttribute()
    {
        return $this->latest_contracting_date->addYear()->subDay();
    }

    public function getNextContractingDateAttribute()
    {
        return $this->end_of_contract_date->addDay();
    }

    // Relations
    public function contactInfo()
    {
        return $this->hasOne(EmployeeContactInfo::class, 'employee_id');
    }

    public function financialInfo()
    {
        return $this->hasOne(EmployeeFinancialInfo::class, 'employee_id');
    }

    public function capabilitiesInfo()
    {
        return $this->hasOne(EmployeeCapabilitiesInfo::class, 'employee_id');
    }

    public function qualificationsInfo()
    {
        return $this->hasMany(EmployeeQualificationInfo::class, 'employee_id');
    }

    public function academicQualifications()
    {
        return $this->hasMany(EmployeeQualificationInfo::class, 'employee_id')->where('type', 'Academic');
    }

    public function professionalQualifications()
    {
        return $this->hasMany(EmployeeQualificationInfo::class, 'employee_id')->where('type', 'Professional');
    }

    public function spouseInfo()
    {
        return $this->hasOne(EmployeeSpouseInfo::class, 'employee_id');
    }

    public function parentsInfo()
    {
        return $this->hasOne(EmployeeParentsInfo::class, 'employee_id');
    }

    public function jobInfo()
    {
        return $this->hasOne(EmployeeJobInfo::class, 'employee_id');
    }

    public function childrenInfo()
    {
        return $this->hasOne(EmployeeChildrenInfo::class, 'employee_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getDocumentsPathsAttribute()
    {
        $paths = [];

        foreach ($this->documents as $document) {
            if ($media = $this->getMedia($document->document_type)->first()) {
                $paths[$document->document_type] = config('app.url').$media->getUrl();
            }
        }

        return $paths;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function depart()
    {
        return $this->jobInfo ? $this->jobInfo->department : null;
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, EmployeeJobInfo::class, 'employee_id', 'id', 'id', 'department_id');
    }

    public function user_branch()
    {
        return $this->hasOneThrough(Branch::class, EmployeeJobInfo::class, 'employee_id', 'id', 'id', 'branch_id');
    }

    public function branch()
    {
        return $this->jobInfo ? $this->jobInfo->branch : null;
    }

    public function employment_level()
    {
        return $this->hasOneThrough(EmploymentLevel::class, EmployeeJobInfo::class, 'employee_id', 'id', 'id', 'employment_level_id');
    }

    public function report_to()
    {
        return $this->hasOneThrough(self::class, EmployeeJobInfo::class, 'employee_id', 'id', 'id', 'report_to_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function thumbs()
    {
        return $this->hasMany(Thumb::class);
    }

    public function fingerprints()
    {
        return $this->hasManyThrough(Fingerprint::class, Thumb::class, 'employee_id', 'staff_id', 'id', 'staff_id');
    }

    // Scopes
    public function scopeAgeBetween(Builder $query, $age_range = [])
    {
        $minDate = Carbon::now()->subYears($age_range[1])->minute(0)->second(0)->hour(0); //->day(1)->month(1);
        $maxDate = Carbon::now()->subYears($age_range[0])->minute(0)->second(0)->hour(0); //->day(1)->month(1);

        return $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
    }

    public function scopeSeniorityBetween(Builder $query, $seniority_range = [])
    {
        $minDate = Carbon::now()->subYears($seniority_range[1])->minute(0)->second(0)->hour(0); //->day(1)->month(1);
        $maxDate = Carbon::now()->subYears($seniority_range[0])->minute(0)->second(0)->hour(0); //->day(1)->month(1);

        return $query->whereBetween('date_of_join', [$minDate, $maxDate]);
    }

    public function scopeHasNationalityOf(Builder $query, $nationalities = [])
    {
        return $query->whereIn('nationality', $nationalities);
    }

    public function scopeWithPassport(Builder $query, $passport_no)
    {
        return $query->whereHas('documents', function (Builder $q) use ($passport_no) {
            return $q->where('document_type', 'PASSPORT')->where('document_no', 'like', "%$passport_no%");
        });
    }

    public function scopeInOneOfDepartments(Builder $query, $department_ids)
    {
        return $query->whereHas('jobInfo', function (Builder $q) use ($department_ids) {
            return $q->whereIn('department_id', $department_ids);
        });
    }

    public function scopeInOneOfBranches(Builder $query, $branch_id)
    {
        return $query->whereHas('jobInfo', function (Builder $q) use ($branch_id) {
            return $q->whereIn('branch_id', \is_array($branch_id) ? $branch_id : [$branch_id]);
        });
    }

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/HR/employees/'.$this->id.'/edit';
    }

    public function get_editted_url()
    {
        return config('app.url').'/HR/employees/'.$this->id.'/edit';
    }

    public function get_title()
    {
        return $this->full_name;
    }
}
