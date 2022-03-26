<?php

namespace App\Http\Controllers\Backend;

use App\Models\Port;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Transformers\PortTransformer;
use App\Http\Requests\StorePortRequest;
use App\Repositories\Contracts\PortRepository;

class PortController extends BackendController
{
    protected $ports;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(PortRepository $ports)
    {
        $this->ports = $ports;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorePortRequest $request)
    {
        $this->authorize('create ports');
        $this->ports->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.ports.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Port $port
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Port $port)
    {
        return $port;
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
        $requestSearchQuery = new RequestSearchQuery($request, $this->ports->query(), ['country', 'name']);

//        return $requestSearchQuery->result([
//            'id',
//            'name',
//            'imo_number',
//            'port_of_registry',
//            'flag',
//            'created_at',
//            'updated_at',
//        ]);

        return $requestSearchQuery->result(new PortTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Port         $port
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Port $port, Request $request)
    {
        $this->authorize('edit ports');

        $this->ports->update($port, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.ports.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Port $port
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Port $port, Request $request)
    {
        $this->authorize('delete ports');

        $this->ports->destroy($port);

        return $this->redirectResponse($request, __('alerts.backend.ports.deleted'));
    }
}
