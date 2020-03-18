<?php

namespace NeptuneSoftware\Invoicable\Providers;

use Illuminate\Support\ServiceProvider;
use NeptuneSoftware\Accounting\Interfaces\AccountingServiceInterface;
use NeptuneSoftware\Accounting\Services\Accounting;

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
