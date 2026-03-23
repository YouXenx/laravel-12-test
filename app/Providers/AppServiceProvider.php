<?php

namespace App\Providers;

use App\Interfaces\EventParticipantRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\HeadOfFamilyRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\SocialAssistanceRepositoryInterface;
use App\Repositories\EventParticipantRepository;
use App\Repositories\SocialAssistanceRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(HeadOfFamilyRepositoryInterface::class, HeadOfFamilyRepository::class);
        $this->app->bind(SocialAssistanceRepositoryInterface::class, SocialAssistanceRepository::class);
        $this->app->bind(EventParticipantRepositoryInterface::class, EventParticipantRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
