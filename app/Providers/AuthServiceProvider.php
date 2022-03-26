<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Task;
use App\Models\User;
use App\Models\Leave;
use App\Models\Vessel;
use App\Models\Activity;
use App\Models\Employee;
use App\Models\Seafarer;
use App\Policies\EquipmentPolicy;
use App\Policies\InventoryPolicy;
use App\Policies\InventoryTransactionPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use App\Policies\LeavePolicy;
use App\Policies\VesselPolicy;
use App\Models\CashRequisition;
use App\Models\SeafarerContract;
use App\Policies\ActivityPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\SeafarerPolicy;
use App\Models\FlightReservation;
use App\Models\PaymentRequisition;
use App\Models\PurchaseRequisition;
use Illuminate\Support\Facades\Gate;
use App\Policies\CashRequisitionPolicy;
use App\Policies\SeafarerContractPolicy;
use App\Policies\FlightReservationPolicy;
use App\Policies\PaymentRequisitionPolicy;
use App\Policies\PurchaseRequisitionPolicy;
use App\Repositories\Contracts\AccountRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies
        = [
            User::class                 => UserPolicy::class,
            SeafarerContract::class     => SeafarerContractPolicy::class,
            Vessel::class               => VesselPolicy::class,
            Seafarer::class             => SeafarerPolicy::class,
            Task::class                 => TaskPolicy::class,
            Activity::class             => ActivityPolicy::class,
            CashRequisition::class      => CashRequisitionPolicy::class,
            PaymentRequisition::class   => PaymentRequisitionPolicy::class,
            PurchaseRequisition::class  => PurchaseRequisitionPolicy::class,
            FlightReservation::class    => FlightReservationPolicy::class,
            Employee::class             => EmployeePolicy::class,
            Leave::class                => LeavePolicy::class,
            Inventory::class                => InventoryPolicy::class,
            InventoryTransaction::class                => InventoryTransactionPolicy::class,
            Equipment::class                => EquipmentPolicy::class,
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        $this->registerPolicies();

        $accountRepository = $this->app->make(AccountRepository::class);

        foreach (array_keys(config('permissions')) as $permission) {
            Gate::define($permission,function (User $user) use ($accountRepository, $permission) {
                return $accountRepository->hasPermission($user, $permission);
            });
        }

        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            return $user->id == 1;
        });
    }
}
