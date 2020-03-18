<?php

namespace NeptuneSoftware\Invoicable\Providers;

use Illuminate\Support\ServiceProvider;
use NeptunSoftware\Accounting\Interfaces\AccountingServiceInterface;
use NeptunSoftware\Accounting\Services\Accounting;

class AccountingServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AccountingServiceInterface::class, function ($app) {
            return new Accounting();
        });
    }
}
