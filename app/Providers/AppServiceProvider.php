<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\DocTemplate;
use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use App\Models\InventoryTransaction;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Leave;
use App\Models\Vessel;
use App\Models\Activity;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Seafarer;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\CrewRequest;
use App\Models\CashRequisition;
use App\Models\EmployeeJobInfo;
use App\Models\SeafarerContract;
use App\Models\FlightReservation;
use App\Models\EmployeeSpouseInfo;
use App\Models\PaymentRequisition;
use App\Models\EmployeeContactInfo;
use App\Models\EmployeeParentsInfo;
use App\Models\PurchaseRequisition;
use Illuminate\Support\Facades\URL;
use App\Models\EmployeeChildrenInfo;
use App\Models\EmployeeFinancialInfo;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\EmployeeCapabilitiesInfo;
use App\Models\EmployeeQualificationInfo;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'User'                      => User::class,
            'Seafarer'                  => Seafarer::class,
            'Document'                  => Document::class,
            'EmployeeCapabilitiesInfo'  => EmployeeCapabilitiesInfo::class,
            'EmployeeFinancialInfo'     => EmployeeFinancialInfo::class,
            'EmployeeContactInfo'       => EmployeeContactInfo::class,
            'EmployeeSpouseInfo'        => EmployeeSpouseInfo::class,
            'EmployeeChildrenInfo'      => EmployeeChildrenInfo::class,
            'EmployeeQualificationInfo' => EmployeeQualificationInfo::class,
            'EmployeeParentsInfo'       => EmployeeParentsInfo::class,
            'EmployeeJobInfo'           => EmployeeJobInfo::class,
            'CrewRequest'               => CrewRequest::class,
            'Role'                      => Role::class,
            'Vessel'                    => Vessel::class,
            'SeafarerContract'          => SeafarerContract::class,
            'Task'                      => Task::class,
            'Activity'                  => Activity::class,
            'PurchaseRequisition'       => PurchaseRequisition::class,
            'CashRequisition'           => CashRequisition::class,
            'PaymentRequisition'        => PaymentRequisition::class,
            'FlightReservation'         => FlightReservation::class,
            'Department'                => Department::class,
            'Attendance'                => Attendance::class,
            'Employee'                  => Employee::class,
            'Leave'                     => Leave::class,
            'Announcement'              => Announcement::class,
            'DocTemplate'               => DocTemplate::class,
            'Inventory'                 => Inventory::class,
            'InventoryTransaction'      => InventoryTransaction::class,
            'InventoryItem'             => InventoryItem::class,
            'InventoryItemCategory'     => InventoryItemCategory::class,
            'Equipment'                 => Equipment::class,
        ]);

        if (config('app.url_force_https')) {
            // Force SSL if isSecure does not detect HTTPS
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Dusk, if env is appropriate
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
