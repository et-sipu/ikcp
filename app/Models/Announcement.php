<?php

namespace App\Models;

use App\Models\Traits\GetAttachmentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Announcement extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;
    use GetAttachmentsTrait;

    protected $fillable = [
		'subject',
		'content',
		'destination',
        'cc',
		'status',
        'published_at'
    ];
    protected $casts = [
        'destination' => 'array'
    ];

    protected $dates = ['published_at'];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true;//Gate::check('edit announcements');
    }

    public function getCanDeleteAttribute()
    {
        return true;//Gate::check('delete announcements');
    }

    public function scopeMyAnnouncements(Builder $query, $user_id){
        $user =  User::where('id', '=', $user_id)->first();
        $user_branch = $user->employee->user_branch->id;
        $user_dep= $user->employee->department->id;
        $user_email= $user->email;
        $user_groups= $user->groups;
        $query->where('status','=','PUBLISHED');

        $query->where(static function($query) use($user_dep,$user_branch,$user_email,$user_groups){
            $query->where(static function($query) use($user_dep){
                $query->whereRaw('JSON_EXTRACT(destination,\'$.destination_type\') = "DEPARTMENT"')->
                whereRaw('JSON_SEARCH(json_extract(destination,"$.to"),"one",?,null,"$[*].id") is not null',[$user_dep]);
            });

            $query->orwhere(static function ($query) use($user_groups){
                $query->whereRaw('JSON_EXTRACT(destination,\'$.destination_type\') = "GROUPS"');
                $query->where(function($query) use($user_groups){
                    foreach ($user_groups as $user_group) {
                        $query->orWhereRaw('JSON_SEARCH(json_extract(destination,"$.to"),"one",?,null,"$[*].id") is not null', [$user_group->id]);
                    }
                });
            });

            $query->orwhere(static function($query) use($user_email){
                $query->whereRaw('JSON_EXTRACT(destination,\'$.destination_type\') = "USERS"')->
                whereRaw('JSON_SEARCH(json_extract(destination,"$.to"),"all",?) is not null',[$user_email]);
            });
            $query->orWhere(static function($query) use($user_branch){
                $query->whereRaw('JSON_EXTRACT(destination,\'$.destination_type\') = "LOCATION"')->
                whereRaw('JSON_SEARCH(json_extract(destination,"$.to"),"one",?,null,"$[*].id") is not null',[$user_branch]);
            });

            $query->orWhereRaw('JSON_EXTRACT(destination,\'$.destination_type\') = "ALL"');
        });

        return $query;
    }

   // Relations

    // Scopes

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/HR/announcements';
    }

}
