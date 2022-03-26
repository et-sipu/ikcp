<?php

namespace App\Models;

use App\Models\Traits\WorkflowTrait;
use Illuminate\Support\Facades\Gate;
use App\Models\Contracts\Commentable;
use App\Models\Contracts\Workflowable;
use App\Models\Traits\CommentableTrait;
use App\Models\Traits\RequestableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Traits\GetAttachmentsTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class CashRequisition extends Model implements HasMedia, Auditable, Workflowable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasMediaTrait;
    use WorkflowTrait;
    use CommentableTrait;
    use RequestableTrait;
    use GetAttachmentsTrait;

    public static $initial_state = 'hod_approving';
    public static $minimal_print_state = 'eco_approving';

    protected $fillable = [
        'requester_id',
        'employee_id',
        'purpose',
        'request_type',
        'amount',
        'approved_amount',
        'cash_advance_taken',
        'cash_advance_taken_date',
        'total_receipt_returned',
        'total_receipt_returned_date',
        'outstanding_ca',
        'status',
        'remarks',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanViewAttribute()
    {
        return Gate::check('view', $this);
    }

    public function getCanPassAttribute()
    {
        return Gate::check('pass', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete', $this);
    }

    public function getCanPrintAttribute()
    {
        return Gate::check('print', $this);
//        return Gate::check('print cash requisitions') && $this->reached_minimum_print_state() && ! \in_array($this->status, ['rejected', 'closed'], true);
    }

    public function getCanPassUrgentlyAttribute()
    {
        return Gate::check('pass cash requisitions urgently');
    }

    public function getReceiptsInfoAttribute()
    {
        $receipts = [];

        $medias = $this->getMedia('receipts')->all();

        foreach ($medias as $media) {
            array_push($receipts,
                [
                    'id'   => isset($media) ? $media->id : 0,
                    'file' => null,
                    'url'  => isset($media) ? config('app.url').$media->getUrl() : null,
                ]);
        }

        return $receipts;
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
        return '/Finance/cash_requisitions/'.$this->id.'/edit';
    }


    public function get_editted_url()
    {
        return $this->get_edit_url();
    }

    public function get_title()
    {
        return $this->purpose;
    }

    public function getStackHolders()
    {
        $users = [];
        $users[$this->requester->email] = $this->requester->email;
        $users[$this->requester->employee->department->hod->email] = $this->requester->employee->department->hod->email;

        return array_merge($this->get_receivers()->pluck('email', 'email')->toArray(), $users);
    }
}
