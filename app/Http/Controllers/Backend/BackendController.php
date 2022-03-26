<?php

namespace App\Http\Controllers\Backend;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\AuditTransformer;
use App\Transformers\CommentTransformer;

class BackendController extends Controller
{
    protected $repo_name;

    /**
     * Show admin home.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.home');
    }

    /**
     * @param Request $request
     * @param $message
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function redirectResponse(Request $request, $message, $type = 'success')
    {
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => $type,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with("flash_{$type}", $message);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $commentable_id)
    {
        //$this->authorize('add_comment', $seafarer);

        $usersRepository = app()->make('App\Repositories\Contracts\UserRepository');

        $usersRepository->addComment($request->input(), $this->{$this->repo_name}->query()->find($commentable_id));

        return $this->redirectResponse($request, __('alerts.backend.others.comment_created'));
    }

    /**
     * @param Request $request
     * @param $commentable_id
     *
     * @return mixed
     */
    public function getComments(Request $request, $commentable_id)
    {
        $query = $this->{$this->repo_name}->query()->findOrFail($commentable_id)->comments()->orderBy('id', 'desc');

        if ($request->has('current_page') && $request->has('per_page')) {
            $query = $query->limit($request->get('per_page'))->offset($request->get('per_page') * $request->get('current_page'));
        }

        return Fractal::create()->collection($query->get())
            ->transformWith(new CommentTransformer())
            ->toArray();
    }

    public function getHistory(Request $request, $auditable_id)
    {
        $query = $this->{ $this->repo_name}->query()->find($auditable_id)->audits();

        if ($request->has('current_page') && $request->has('per_page')) {
            $query = $query->limit($request->get('per_page'))->offset($request->get('per_page') * $request->get('current_page'));
        }

        return Fractal::create()->collection($query->get())
            ->transformWith(new AuditTransformer())
            ->toArray();
    }
}
