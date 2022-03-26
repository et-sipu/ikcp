<?php

namespace App\Providers;

use App\Events\AnnouncementPublished;
use App\Events\CommentAdded;
use App\Events\TaskAssigned;
use App\Events\EmployeeUpdated;
use App\Listeners\AnnouncementPublishedListener;
use Illuminate\Auth\Events\Login;
use App\Events\IMOReportGenerated;
use App\Listeners\UserEventListener;
use App\Listeners\LoginEventListener;
use App\Listeners\PRFApprovedListener;
use App\Events\SeafarerContractCreated;
use App\Events\WorkflowableTransmitted;
use App\Listeners\EmployeeUpdatedListener;
use App\Listeners\CommentAddedEventListener;
use App\Listeners\TaskAssignedEventListener;
use App\Events\SeafarerContractStatusChanged;
use App\Listeners\IMOReportGeneratingEventListener;
use App\Listeners\SeafarerContractCreatedEventListener;
use App\Listeners\WorkflowableTransmittedEventListener;
use App\Listeners\SeafarerContractStatusChangedEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class => [
            LoginEventListener::class,
        ],
        SeafarerContractStatusChanged::class => [
            SeafarerContractStatusChangedEventListener::class,
        ],
        SeafarerContractCreated::class => [
            SeafarerContractCreatedEventListener::class,
        ],
        IMOReportGenerated::class => [
            IMOReportGeneratingEventListener::class,
        ],
        TaskAssigned::class => [
            TaskAssignedEventListener::class,
        ],
        CommentAdded::class => [
            CommentAddedEventListener::class,
        ],
        WorkflowableTransmitted::class => [
            WorkflowableTransmittedEventListener::class,
//            PRFApprovedListener::class,
        ],
        EmployeeUpdated::class => [
            EmployeeUpdatedListener::class,
        ],
        AnnouncementPublished::class => [
            AnnouncementPublishedListener::class
        ]
    ];

    /**
     * Class event subscribers.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventListener::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
