<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyRegistrations;
use App\Transformers\CompanyRegistrationsTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreCompanyRegistrationsRequest;
use App\Http\Requests\UpdateCompanyRegistrationsRequest;
use App\Repositories\Contracts\CompanyRegistrationsRepository;
use Spatie\Fractal\Fractal;
use Illuminate\Support\Facades\Mail;

use App\Mail\CompanyRegistrationsExpiryNotification;

class CompanyRegistrationsController extends BackendController
{

    /**
     * @var CompanyRegistrationsRepository
     */
    protected $company_registrations;

    /**
     * Create a new controller instance.
     *
     * @param CompanyRegistrationsRepository $company_registrations
     */
    public function __construct(CompanyRegistrationsRepository $company_registrations)
    {
        $this->company_registrations = $company_registrations;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     *
     */
    public function search(Request $request)
    {
        $query = $this->company_registrations->query()->with([]);
        
        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new CompanyRegistrationsTransformer());
    }

    /**
     * @param CompanyRegistrations $company_registration
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(CompanyRegistrations $company_registration)
    {
         $this->authorize('view company registrations');

        return Fractal::create()->item($company_registration, new CompanyRegistrationsTransformer());
    }

    /**
     * @param StoreCompanyRegistrationsRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCompanyRegistrationsRequest $request)
    {
         $this->authorize('create company registrations');

        $this->company_registrations->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.company_registrations.created'));
    }

    /**
     * @param CompanyRegistrations $company_registration
     * @param UpdateCompanyRegistrationsRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CompanyRegistrations $company_registration, UpdateCompanyRegistrationsRequest $request)
    {
         $this->authorize('edit company registrations');

        $this->company_registrations->update($company_registration, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.company_registrations.updated'));
    }

    /**
     * @param CompanyRegistrations $company_registration
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CompanyRegistrations $company_registration, Request $request)
    {
         $this->authorize('delete company registrations');

        $this->company_registrations->destroy($company_registration);

        return $this->redirectResponse($request, __('alerts.backend.company_registrations.deleted'));
    }


    public function document_expiration(){

       $documents= CompanyRegistrations::all();
        $current_month = now();
        $two_month = now()->addMonth(2);

        $expired=[];
        $warning=[];
        if($documents) {
            foreach ($documents as $document) {
                $expiry_date= new Carbon($document->validity_to);

                if ($expiry_date <= $current_month->toDateString()) {
                    array_push($expired, $document->toArray());
                }
                elseif ($expiry_date <= $two_month->toDateString()){
                    array_push($warning, $document->toArray());
                }

            }
            Mail::to(['besher@inaikiara.com.my',
                'sara@inaikiara.com.my',
                'azizali@inaikiara.com.my',
                'jessie@inaikiara.com.my',
                'anisa@inaikiara.com.my',
                'fakrimi@inaikiara.com.my'

            ])
//                    ->cc(['a.alkhoudary@inaikiara.com.my', 'besher@inaikiara.com.my'])
                ->send(new CompanyRegistrationsExpiryNotification($expired,$warning));
        }

    }
}