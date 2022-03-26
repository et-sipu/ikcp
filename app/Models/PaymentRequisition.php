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
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class PaymentRequisition extends Model implements HasMedia, Auditable, Workflowable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasMediaTrait;
    use WorkflowTrait;
    use CommentableTrait;
    use RequestableTrait;

    public static $initial_state = 'hod_approving';
    public static $minimal_print_state = 'md_approving';

    protected $fillable = [
        'requester_id',
        'employee_id',
        'released_to',
        'title',
        'details',
        'remarks',
        'project',
        'supplier',
        'last_pv_no',
        'last_payment_amount',
        'last_payment_date',
        'old_outstanding_invoices',
        'new_outstanding_invoices',
        'currency',
        'payment',
        'approved_payment',
        'cheque_no',
        'cheque_bank',
        'cheque_date',
        'status',
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
//        return Gate::check('print payment requisitions') && $this->reached_minimum_print_state() && ! \in_array($this->status, ['rejected', 'closed'], true);
    }

    public function getCanPassUrgentlyAttribute()
    {
        return Gate::check('pass payment requisitions urgently');
    }

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
                ]);
        }

        return $attachments;
    }

    public function getPaymentVoucherUrlAttribute()
    {
        if ($media = $this->getMedia('payment_voucher')->first()) {
            return config('app.url').$media->getUrl();
        }
    }

    // Relations
    public function flight_reservations()
    {
        return $this->hasMany(FlightReservation::class, 'payment_requisition_id');
    }

    // Scopes

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/Finance/payment_requisitions/'.$this->id.'/edit';
    }

    public function get_editted_url()
    {
        return $this->get_edit_url();
    }

    public function get_title()
    {
        return $this->title;
    }

    public function getStackHolders()
    {
        $users = [];
        $users[$this->requester->email] = $this->requester->email;
        $users[$this->requester->employee->department->hod->email] = $this->requester->employee->department->hod->email;

        return array_merge($this->get_receivers()->pluck('email', 'email')->toArray(), $users);
    }
}
