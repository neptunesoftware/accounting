<?php

namespace NeptuneSoftware\Invoicable\Providers;

use Illuminate\Support\ServiceProvider;
use Scottlaurent\Accounting\Services\Accounting;
use Scottlaurent\Accounting\Services\Interfaces\AccountingServiceInterface;

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
