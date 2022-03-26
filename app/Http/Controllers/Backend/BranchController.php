<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Transformers\BranchTransformer;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Repositories\Contracts\BranchRepository;

class BranchController extends BackendController
{
    /**
     * @var BranchRepository
     */
    protected $branches;

    /**
     * Create a new controller instance.
     *
     * @param BranchRepository $branches
     */
    public function __construct(BranchRepository $branches)
    {
        $this->branches = $branches;
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
        $query = $this->branches->query()->with([]);

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new BranchTransformer());
    }

    /**
     * @param Branch $branch
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Branch $branch)
    {
        $this->authorize('view branches');

        return Fractal::create()->item($branch, new BranchTransformer());
    }

    /**
     * @param StoreBranchRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreBranchRequest $request)
    {
        $this->authorize('create branches');

        $this->branches->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.branches.created'));
    }

    /**
     * @param Branch              $branch
     * @param UpdateBranchRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Branch $branch, UpdateBranchRequest $request)
    {
        $this->authorize('edit branches');

        $this->branches->update($branch, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.branches.updated'));
    }

    /**
     * @param Branch  $branch
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Branch $branch, Request $request)
    {
        $this->authorize('delete branches');

        $this->branches->destroy($branch);

        return $this->redirectResponse($request, __('alerts.backend.branches.deleted'));
    }
}
