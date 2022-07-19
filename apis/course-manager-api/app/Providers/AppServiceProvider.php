<?php

namespace App\Providers;

use App\Http\Controllers\Providers\Forex\Fixer\FixerForexCommandController;
use App\Http\Controllers\Providers\Forex\ForexProviders;
use App\Http\Controllers\Providers\Forex\IForex;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Providers\LeadManagement\LeadProviders;
use App\Http\Controllers\Providers\LeadManagement\Zoho\ZohoLeadProvider;
use App\Http\Controllers\Providers\MailList\IMailingListProvider;
use App\Http\Controllers\Providers\MailList\MailJet\MailJetMailingListProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(config('craydle.default_lms_provider') == LeadProviders::ZOHO_LEAD_PROVIDER){
            $this->app->bind(ILeadProvider::class, ZohoLeadProvider::class);
        }

        if(config('craydle.default_forex_provider') == ForexProviders::FIXER_FOREX_API){
            $this->app->bind(IForex::class, FixerForexCommandController::class);
        }

        $this->app->bind(IMailingListProvider::class, MailJetMailingListProvider::class);
    }
}
