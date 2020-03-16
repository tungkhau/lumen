<?php

namespace App\Providers;

use App\Interfaces\DeviceInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\WorkplaceInterface;
use App\Repositories\DeviceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkplaceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(DeviceInterface::class, DeviceRepository::class);
        $this->app->bind(WorkplaceInterface::class, WorkplaceRepository::class);
    }

    public function boot()
    {

    }
}
