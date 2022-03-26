<?php

namespace App\Http\Controllers\Backend;

use App\Transformers\NotificationTransformer;
use App\Utils\RequestSearchQuery;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Spatie\Fractal\Fractal;

class NotificationController
{
    /**
     * @param Request $request
     * @return array
     */
    public function getUnreadNotificationsCount(Request $request)
    {
        return ['count' => auth()->user()->unreadNotifications->count()];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getUnreadNotificationsCountByType(Request $request)
    {
        $query = auth()->user()->notifications()->getQuery();

        $query->getQuery()->orders = null;

        $distinct_query = $query;

        $notification_types = $distinct_query->select(DB::raw('distinct type'))->get()->pluck('type')->toArray();

        $results = $query->select(DB::raw('type,count(*) as count'))->whereNull('read_at')->groupBy('type')->get()->pluck('count', 'type')->toArray();

        $res = [];

        array_map(function($type) use(&$res, $results){
            $res[] = [
                'icon' =>__('labels.backend.notifications.icons.'.$type),
                'actual_type' =>$type,
                'type' =>__('labels.backend.notifications.types.'.$type),
                'count' => isset($results[$type]) ? $results[$type] : 0
            ];
        },$notification_types);

        usort($res, function($a, $b){
            return strcmp($a['type'], $b['type']);
        });

        return $res;
    }

    /**
     * @param Request $request
     * @param bool $latest
     * @return array
     */
    public function getNotifications(Request $request, $latest = '')
    {
        $query = auth()->user()->notifications()->getQuery()->latest();

        if($latest == 'latest') {
            $request->merge(['perPage' => 5]);
            $query->whereNull('read_at');
        }

        if($request->has('type') && $request->get('type')){
            $query->whereType($request->get('type'));
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new NotificationTransformer());
    }

    /**
     * @param Request $request
     * @param DatabaseNotification $notification
     * @param $mark_as
     * @return array
     */
    public function toggleNotificationStatus(Request $request, DatabaseNotification $notification, $mark_as)
    {
        if(($notification->read() && $mark_as === 'non') || $mark_as === 'unread') $notification->markAsUnread();
        elseif(($notification->unread() && $mark_as === 'non') || $mark_as === 'read') $notification->markAsRead();

        return ['status' => 'ok'];
    }

    /**
     * @param Request $request
     * @param $mark_as
     * @return array
     */
    public function bulkToggleNotificationStatus(Request $request, $mark_as)
    {
        if($request->has('notifications') && is_array($request->get('notifications')) && count($request->get('notifications')) > 0){

            $notifications = auth()->user()->notifications()->whereIn('id', $request->get('notifications'))->get();

            $notifications->each(function($notification) use($mark_as){
                if($mark_as == 'read'){
                    $notification->markAsRead();
                } else {
                    $notification->markAsUnRead();
                }
            });
        }

        return ['status' => 'ok'];
    }

    /**
     * @param Request $request
     * @param DatabaseNotification $notification
     * @return \Spatie\Fractalistic\Fractal
     */
    public function getNotification(Request $request, DatabaseNotification $notification)
    {
        return Fractal::create()->item($notification, new NotificationTransformer());
    }

}
