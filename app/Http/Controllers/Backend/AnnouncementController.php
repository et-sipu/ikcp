<?php

namespace App\Http\Controllers\Backend;

use App\Models\Announcement;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Transformers\AnnouncementTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Repositories\Contracts\AnnouncementRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Spatie\Fractal\Fractal;
use App\Mail\AnnouncementMail;


class AnnouncementController extends BackendController
{

    /**
     * @var AnnouncementRepository
     */
    protected $announcements;

    /**
     * Create a new controller instance.
     *
     * @param AnnouncementRepository $announcements
     */
    public function __construct(AnnouncementRepository $announcements)
    {
        $this->announcements = $announcements;
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
        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new AnnouncementTransformer());
    }

    /**
     * @param Announcement $announcement
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Announcement $announcement)
    {
        $this->authorize('view announcements');

        return Fractal::create()->item($announcement, new AnnouncementTransformer());
    }

    /**
     * @param StoreAnnouncementRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreAnnouncementRequest $request)
    {
        $this->authorize('create announcements');

        $this->announcements->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.announcements.created'));
    }

    /**
     * @param Announcement $announcement
     * @param UpdateAnnouncementRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Announcement $announcement, UpdateAnnouncementRequest $request)
    {
        $this->authorize('edit announcements');

        $this->announcements->update($announcement, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.announcements.updated'));
    }

    /**
     * @param Announcement $announcement
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Announcement $announcement, Request $request)
    {
        $this->authorize('delete announcements');

        $this->announcements->destroy($announcement);

        return $this->redirectResponse($request, __('alerts.backend.announcements.deleted'));
    }

    /**
     * @param Request $request
     * @param Announcement $announcement
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publish(Request $request, Announcement $announcement)
    {
        $this->authorize('publish announcements');

        $this->announcements->publish($announcement);

        return $this->redirectResponse($request, __('alerts.backend.announcements.published'));
    }

    /**
     * @return Builder
     */
    public function extractQuery(): Builder
    {
        $query = $this->announcements->query();

        if (!Gate::check('view announcements')) {
            $query->MyAnnouncements(auth()->user()->id);
        }
        return $query;
    }
}