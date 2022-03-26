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

class FlightReservation extends Model implements HasMedia, Auditable, Workflowable, Commentable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasMediaTrait;
    use WorkflowTrait;
    use CommentableTrait;
    use RequestableTrait;

    protected $fillable = [
        'requester_id',
        'employee_id',
        'form_type',
        'form_target',
        'departure_from',
        'departure_to',
        'transportation_cost',
        'passengers',
        'purpose',
        'status',
        'price',
        'deduction',
    ];

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

//    public function getBudgetedPriceAttribute()
//    {
//        $budgeted_price = 0;
//
//        $this->trip_item->each(function ($trip) use (&$budgeted_price) {
//            if ('FLIGHT' === $trip->trip_type) {
//                $quotation = $trip->quotations()->orderBy('price')->first();
//                if (! $quotation) {
//                    $price = 0;
//                } else {
//                    $price = $quotation->price;
//                }
//            } else {
//                $price = $trip->trip_attributes['price'];
//            }
//            $budgeted_price += $price;
//        });
//
//        return $budgeted_price;
//    }



    public function getPassengersCountAttribute()
    {
        return $this->getMedia('passengers')->count();
    }

    public function getBudgetedPriceAttribute()
    {
        $budgeted_price = 0;

        $this->trip_item->each(function ($trip) use (&$budgeted_price) {
            $budgeted_price += isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0;
        });

        return $budgeted_price * $this->passengers_count;
    }

    public function getTotalPriceAttribute()
    {
        $passengers_count = $this->getMedia('passengers')->count();

        return $this->price * $this->passengers_count;
    }

    // Relations
    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'form_target');
    }

//    public function quotations()
//    {
//        return $this->hasManyThrough(FlightReservationQuotation::class, TripItem::class, 'flight_reservation_id', 'trip_item_id', 'id', 'id');
//    }

    public function prf()
    {
        return $this->belongsTo(PaymentRequisition::class, 'payment_requisition_id');
    }

    public function trip_item()
    {
        return $this->hasMany(TripItem::class);
    }

    // Scopes

    // Methods
    public function get_edit_url()
    {
        return config('app.url').$this->get_local_edit_url();
    }

    public function get_local_edit_url()
    {
        return '/AdminForms/flight_reservations/'.$this->id.'/edit';
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
        return [];
        $users = [];
        $users[$this->requester->email] = $this->requester->email;
        $users[$this->requester->employee->department->hod->email] = $this->requester->employee->department->hod->email;

        return array_merge($this->get_receivers()->pluck('email', 'email')->toArray(), $users);
    }

    public function trip()
    {
        return $this->hasMany(TripItem::class);
    }
}
