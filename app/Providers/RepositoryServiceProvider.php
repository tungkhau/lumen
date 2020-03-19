<?php

namespace App\Providers;

use App\Interfaces\AccessoryInterface;
use App\Interfaces\BlockInterface;
use App\Interfaces\CaseInterface;
use App\Interfaces\ConceptionInterface;
use App\Interfaces\CustomerInterface;
use App\Interfaces\DeviceInterface;
use App\Interfaces\ImportInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\RestorationInterface;
use App\Interfaces\SupplierInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\WorkplaceInterface;
use App\Repositories\AccessoryRepository;
use App\Repositories\BlockRepository;
use App\Repositories\CaseRepository;
use App\Repositories\ConceptionRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ImportRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RestorationRepository;
use App\Repositories\ShelfRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkplaceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Group 1
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(DeviceInterface::class, DeviceRepository::class);
        $this->app->bind(WorkplaceInterface::class, WorkplaceRepository::class);

        //Group 2
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(SupplierInterface::class, SupplierRepository::class);
        $this->app->bind(AccessoryInterface::class, AccessoryRepository::class);
        $this->app->bind(ConceptionInterface::class, ConceptionRepository::class);

        //Group 3
        $this->app->bind(CaseInterface::class, CaseRepository::class);
        $this->app->bind(SupplierInterface::class, ShelfRepository::class);
        $this->app->bind(BlockInterface::class, BlockRepository::class);

        //Group 4
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(ImportInterface::class, ImportRepository::class);
        $this->app->bind(RestorationInterface::class, RestorationRepository::class);

//        $this->app->bind(,);
    }

    public function boot()
    {

    }
}
