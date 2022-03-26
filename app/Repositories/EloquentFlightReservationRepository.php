<?php

namespace App\Repositories;

use Exception;
use App\Models\FlightReservation;
use Illuminate\Support\Facades\DB;
use LynX39\LaraPdfMerger\PdfManage;
use App\Exceptions\GeneralException;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Traits\TransmittableTrait;
use App\Repositories\Traits\SyncAttachmentsTrait;
use App\Repositories\Contracts\FlightReservationRepository;
use App\Repositories\Contracts\PaymentRequisitionRepository;

/**
 * Class EloquentFlightReservationRepository.
 */
class EloquentFlightReservationRepository extends EloquentBaseRepository implements FlightReservationRepository
{
    use SyncAttachmentsTrait;
    use TransmittableTrait;
    /**
     * @var PaymentRequisitionRepository
     */
    private $payment_requisitions;

    /**
     * EloquentFlightReservationRepository constructor.
     *
     * @param FlightReservation            $flight_reservation
     * @param PaymentRequisitionRepository $payment_requisitions
     */
    public function __construct(FlightReservation $flight_reservation, PaymentRequisitionRepository $payment_requisitions)
    {
        parent::__construct($flight_reservation);
        $this->payment_requisitions = $payment_requisitions;
    }

//    /**
//     * @param $name
//     *
//     * @return FlightReservation
//     */
//    public function find($name)
//    {
//        /** @var FlightReservation $flight_reservation */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\FlightReservation
     */
    public function store(array $input)
    {
//        dd($input);
        /** @var FlightReservation $flight_reservation */
        $input['form_target'] = 'VESSEL' === $input['form_type'] ? $input['form_target']['id'] : $input['form_target'];
        $input['deduction'] = '%' === $input['deduction_type'] ? $input['deduction'].$input['deduction_type'] : $input['deduction'];
//        $flight_reservation = $this->make(array_except($input, ['passengers']));
        $flight_reservation = $this->make(array_except($input, ['passengers', 'trips']));
        $flight_reservation->requester_id = auth()->user()->id;
        $flight_reservation->employee_id = auth()->user()->employee->id;

//        if ($this->find($flight_reservation->name)) {
//            throw new GeneralException(__('exceptions.backend.flight_reservations.already_exist'));
//        }

        DB::transaction(function () use ($flight_reservation, $input) {
            if (! $flight_reservation->save()) {
                throw new GeneralException(__('exceptions.backend.flight_reservations.create'));
            }

            $this->sync_trips($flight_reservation, $input['trips']);
            $this->sync_attachments($flight_reservation, $input['passengers'] ? $input['passengers'] : [], 'passengers', ['name', 'designation', 'passport', 'expiry', 'nationality', 'dob', 'hp']);

            return true;
        });

        return $flight_reservation;
    }

    public function sync_trips(FlightReservation $flight_reservation, array $trips)
    {
        $must_be_deleted = $flight_reservation->trip_item()->whereNotIn('id', array_pluck($trips, 'id'));
//        $must_be_deleted->each(function (TripItem $tip_item) {
//            $tip_item->quotations()->delete();
//        });
        $must_be_deleted->delete();

        foreach ($trips as $trip) {
            if (0 !== $trip['id']) {
                $trip_item = $flight_reservation->trip_item()->updateOrCreate(['id' => $trip['id']], $trip);
//                if ('FLIGHT' !== $trip['trip_type']) {
//                    $trip_item->quotations()->delete();
//                }
            }
        }

        return true;
    }

    /**
     * @param FlightReservation $flight_reservation
     * @param array             $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\FlightReservation
     */
    public function update(FlightReservation $flight_reservation, array $input)
    {
//        if (($existingFlightReservation = $this->find($flight_reservation->name))
//            && $existingFlightReservation->id !== $flight_reservation->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.flight_reservations.already_exist'));
//        }

        $input['form_target'] = 'VESSEL' === $input['form_type'] ? $input['form_target']['id'] : $input['form_target'];
        $input['deduction'] = '%' === $input['deduction_type'] ? $input['deduction'].$input['deduction_type'] : $input['deduction'];

        DB::transaction(function () use ($flight_reservation, $input) {
            if (! $flight_reservation->update(array_except($input, ['passengers', 'trips']))) {
                throw new GeneralException(__('exceptions.backend.flight_reservations.update'));
            }
            $this->sync_trips($flight_reservation, $input['trips']);
            $this->sync_attachments($flight_reservation, $input['passengers'] ? $input['passengers'] : [], 'passengers', ['name', 'designation', 'passport', 'expiry', 'nationality', 'dob', 'hp']);

            $this->sync_attachments($flight_reservation, isset($input['receipts']) && $input['receipts'] ? $input['receipts'] : [], 'receipts');

            return true;
        });

        return $flight_reservation;
    }

    /**
     * @param FlightReservation $flight_reservation
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(FlightReservation $flight_reservation)
    {
        if (! $flight_reservation->delete()) {
            throw new GeneralException(__('exceptions.backend.flight_reservations.delete'));
        }

        return true;
    }

    public function get_validation_rules($transition)
    {
        $rules = [];

//        if (preg_match('/^closing$/', $transition)) {
//            $rules = [
//                'deduction'             => 'required|numeric',
//            ];
//        }

        if (preg_match('/^closing$/', $transition)) {
            $rules = [
                'price'             => 'required|numeric',
            ];
        }

        return $rules;
    }

    /**
     * @param FlightReservation $flight_reservation
     * @param $transition
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function check_possibility(FlightReservation $flight_reservation, $transition)
    {
//        if ('budgeted' === $transition) {
//            $flight_reservation->trip_item->each(function ($trip) {
//                if ('FLIGHT' === $trip->trip_type) {
//                    if ($trip->quotations->count() < 1) {
//                        throw new GeneralException(__('exceptions.backend.flight_reservations.insufficient_quote', ['count' => 'one']));
//                    }
//                } else {
//                    if (! $trip->trip_attributes['price'] || ! is_numeric($trip->trip_attributes['price']) || $trip->trip_attributes['price'] <= 0) {
//                        throw new GeneralException(__('exceptions.backend.flight_reservations.invalid_trip_price'));
//                    }
//                }
//            });
//        }

        if ('prf_issued' === $transition) {
            if (! $flight_reservation->prf) {
                throw new GeneralException(__('exceptions.backend.flight_reservations.prf_not_generated'));
            }
        }

//        if($transition == 'closing'){
//            if(!$flight_reservation->price || $flight_reservation->price <= 0) {
//                throw new GeneralException(__('exceptions.backend.flight_reservations.price_must_be_exist'));
//            }
//        }

//        if (preg_match('/closing/', $transition)) {
//            if(!$flight_reservation->getMedia('receipts')->count()) {
//                throw new GeneralException(__('exceptions.backend.cash_requisitions.upload_receipts'));
//            }
//        }

        return false;
    }

    /**
     * @param array $flight_reservations
     *
     * @throws \Throwable
     *
     * @return mixed|void
     */
//    public function generatePRF(FlightReservation $flight_reservation)
    public function generatePRF(array $flight_reservations)
    {
        $flight_reservations_query = $this->query()->whereIn('id', $flight_reservations)->whereNull('payment_requisition_id');
        $flight_reservations = $flight_reservations_query->get();

        $prf_input = [];
        $prf_input['released_to'] = 'CASH';
        $prf_input['title'] = 'ADMIN';

        $prf_input['details'] = "FLIGHT TICKET\n";
        $prf_input['remarks'] = '';
        $prf_input['new_outstanding_invoices'] = 0;
        $prf_input['project'] = '';
        $prf_input['supplier'] = '';

        $files = [];
        $details_letter = 'a';
        $remarks_letter = 'a';

        foreach ($flight_reservations as $flight_reservation) {
            $passengers = $flight_reservation->getMedia('passengers');
            $passengers_count = $passengers->count();

            $prf_input['remarks'] .= "Passengers:\n";
            foreach ($passengers as $passenger) {
                $prf_input['remarks'] .= "($remarks_letter) ".$passenger->getCustomProperty('name')."\n";
                array_push($files, [
                    'file' => $passenger->getPath(),
                    'id'   => null,
                    'url'  => null,
                ]);
                $remarks_letter++;
            }

            $prf_input['project'] .= ('SITE' === $flight_reservation->form_type ? $flight_reservation->form_target : $flight_reservation->vessel->name).' / ';

            $flight_reservation->trip_item->each(function ($trip) use ($passengers_count, &$prf_input, &$details_letter) {
//                if ('FLIGHT' === $trip->trip_type) {
//                    $quotation = $trip->quotations()->orderBy('price')->first();
//                    if (! $quotation) {
//                        $price = 0;
//                    } else {
//                        $price = $quotation->price;
//                        $prf_input['supplier'] .= $quotation->airlines.'('.$quotation->website.') / ';
//                        $prf_input['details'] .= "($details_letter) (FLIGHT) $passengers_count X ".$trip->trip_attributes['departure_from'].' TO '.$trip->trip_attributes['departure_to']." (price $price per ticket)\n";
//                    }
//                } else {

                $price = isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0;
                if ('HOTEL' === $trip->trip_type)
                {
                    $prf_input['details'] .= "($details_letter) (HOTEL) $passengers_count X [ Check In:".$trip->trip_attributes['check_in'].' for '
                        .$trip->trip_attributes['nights'].' nights in '.$trip->trip_attributes['location'].'('.$trip->trip_attributes['hotel'].')'."] (total price $price)\n";
                } elseif ('FLIGHT' === $trip->trip_type)
                {
                    $prf_input['details'] .= "($details_letter) (FLIGHT) $passengers_count X ".$trip->trip_attributes['departure_from'].' TO '.$trip->trip_attributes['departure_to']." (price $price per ticket)\n";
                } else
                {
                    $prf_input['details'] .= "($details_letter) (".$trip->trip_type.") $passengers_count X ".$trip->trip_attributes['transport_from'].' TO '.$trip->trip_attributes['transport_to']." (price $price per ticket)\n";
                }

                //                }
                $details_letter++;
                $prf_input['new_outstanding_invoices'] += $price * $passengers_count;
            });

            $prf_input['currency'] = 'MYR';

            $pdf = SnappyPdf::loadView('SITE' === $flight_reservation->form_type ? 'templates.flight_reservation_form_site' : 'templates.flight_reservation_form_vessel', ['flight_reservation' => $flight_reservation]);

            $frf_local_file_path = $flight_reservation->id.'_flight_reservation_form.pdf';
            $frf_file_path = 'app/'.$flight_reservation->id.'_flight_reservation_form.pdf';
//            $frf_file_path = $flight_reservation->id . '_flight_reservation_form.pdf';

            Storage::disk('local')->delete($frf_local_file_path);

            $pdf->save(storage_path($frf_file_path));

            array_push($files, [
                'file' => storage_path($frf_file_path),
                'id'   => null,
                'url'  => null,
            ]);
        }

        $pdf = SnappyPdf::loadView('templates.flight_reservation_budget', ['flight_reservation_list' => $flight_reservations]);

        $budgeting_file_path = 'app/budgeting.pdf';

        Storage::disk('local')->delete('budgeting.pdf');

        $pdf->setPaper('a4')->setOrientation('landscape')->save(storage_path($budgeting_file_path));

        array_push($files, [
            'file' => storage_path($budgeting_file_path),
            'id'   => null,
            'url'  => null,
        ]);

        $prf_input['attachments'] = $files;

        $prf_input['project'] = preg_replace('/\s\/\s$/', '', $prf_input['project']);
        $prf_input['supplier'] = preg_replace('/\s\/\s$/', '', $prf_input['supplier']);
        $prf_input['details'] = preg_replace('/\n$/', '', $prf_input['details']);
        $prf_input['remarks'] = preg_replace('/\n$/', '', $prf_input['remarks']);
        $prf_input['status'] = 'hod_approving';
        $prf_input['source'] = 'flight_reservation';

        if (0 === mb_strlen($prf_input['supplier'])) {
            $prf_input['supplier'] = 'Multiple websites';
        }

        DB::transaction(function () use ($flight_reservations, $prf_input) {
            $prf = $this->payment_requisitions->store($prf_input);

            $flight_reservations->each(function ($flight_reservation) use ($prf) {
                $flight_reservation->prf()->associate($prf);
                $this->changeStatus($flight_reservation, 'prf_issued');
            });

            return true;
        });

        return true;
    }

    /**
     * @param array $flight_reservations
     *
     * @throws Exception
     *
     * @return mixed|void
     */
    public function generateReport(array $flight_reservations)
    {
        $flight_reservations_query = $this->query()->whereIn('id', $flight_reservations);//->whereStatus('closed');

        $flight_reservations = $flight_reservations_query->get();

        $pdf = SnappyPdf::loadView('templates.flight_reservations_report', ['flight_reservation_list' => $flight_reservations]);

        $pdf->setOrientation('landscape')->save(storage_path('ticket_report.pdf'), true);
        $pdfManager = new PdfManage();
        $pdfManager->addPDF(storage_path('ticket_report.pdf'), 'all', 'L');
        foreach ($flight_reservations as $flight_reservation) {
            foreach ($flight_reservation->getMedia('receipts')->all() as $receipt) {
                $pdfManager->addPDF($receipt->getPath());
            }
        }

        return $pdfManager;
    }
}
