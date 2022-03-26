<?php

namespace App\Http\Controllers\Backend;

use App\Models\Holiday;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Transformers\HolidayTransformer;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Repositories\Contracts\HolidayRepository;

class HolidayController extends BackendController
{
    /**
     * @var HolidayRepository
     */
    protected $holidays;

    /**
     * Create a new controller instance.
     *
     * @param HolidayRepository $holidays
     */
    public function __construct(HolidayRepository $holidays)
    {
        $this->holidays = $holidays;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array
     */
    public function search(Request $request)
    {
        $query = $this->holidays->query()->with([]);

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new HolidayTransformer());
    }

    public function perYear(Request $request)
    {
        $query = $this->holidays->query()->with([]);

        $query->whereYear('start_date', '=', date('Y'));

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new HolidayTransformer(true));
    }

    public function getAsRanges()
    {
        $query = $this->holidays->query()->with([]);

        $query->whereYear('start_date', '=', date('Y'));

        return $query->select(['start_date as from', 'end_date as to'])->get()->toArray();
    }

    /**
     * @param Holiday $holiday
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Holiday $holiday)
    {
        return Fractal::create()->item($holiday, new HolidayTransformer());
    }

    /**
     * @param StoreHolidayRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreHolidayRequest $request)
    {
        $this->authorize('create holidays');

        $this->holidays->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.holidays.created'));
    }

    /**
     * @param Holiday              $holiday
     * @param UpdateHolidayRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Holiday $holiday, UpdateHolidayRequest $request)
    {
        $this->authorize('edit holidays');

        $this->holidays->update($holiday, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.holidays.updated'));
    }

    /**
     * @param Holiday $holiday
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Holiday $holiday, Request $request)
    {
        $this->authorize('delete holidays');

        $this->holidays->destroy($holiday);

        return $this->redirectResponse($request, __('alerts.backend.holidays.deleted'));
    }
}
