<?php

namespace Modules\Email\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Modules\Email\Models\MailTemplateParam;
use Illuminate\Support\Facades\Schema;

use RobersonFaria\DatabaseSchedule\Console\Scheduling\Schedule;
use Modules\Email\Models\Email;
use Modules\Email\Observers\EmailObserver;
use Modules\Email\Observers\UserObserver;

use Illuminate\Support\Facades\Event;
use Modules\Cron\Events\PrepareCronJobs;
use Modules\Email\Models\MailTemplates;
use Modules\Email\Observers\MailTemplateObserver;

use User;


class EmailServiceProvider extends ServiceProvider
{

    protected $moduleName = 'Email';
    protected $moduleNameLower = 'email';


    public function boot()
    {
        
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        // Indlæs tags i konfigurationen ved boot
       // $this->updateMailTemplateParamsConfig();
   
        
        Email::observe(EmailObserver::class);
        
        MailTemplates::observe(MailTemplateObserver::class);

        User::observe(UserObserver::class);

        Email::registerCronJob(['emails:send']);
         

    }


    public function register()
    {


        $this->app->register(RouteServiceProvider::class);


  
        $this->commands([
            \Modules\Email\Console\Commands\SendEmailsComand::class
        ]);


      

    }


    protected function registerConfig()
    {

        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');


        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

       
       
        

    }


    protected function registerViews()
    {

        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');


        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);


        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

    }


    protected function registerTranslations()
    {

        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);


        if (is_dir($langPath)) {

            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);

        } else {

            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }

    }


    private function getPublishableViewPaths(): array
    {

        $paths = [];

        foreach (Config::get('view.paths') as $path) {

            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {

                $paths[] = $path . '/modules/' . $this->moduleNameLower;

            }

        }

        return $paths;

    }


}
