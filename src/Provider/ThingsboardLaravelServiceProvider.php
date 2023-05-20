<?php

namespace JalalLinuX\Tntity\Provider;

use JalalLinuX\Tntity\Facade\DeviceApi;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ThingsboardLaravelServiceProvider extends PackageServiceProvider
{
    const CONTAINER_INSTANCE_PREFIX = 'thingsboard';

    const FACADES = [
        'DeviceApi' => DeviceApi::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('thingsboard')
            ->hasConfigFile('thingsboard')
//            ->hasViews()
//            ->hasViewComponent('spatie', Alert::class)
//            ->hasViewComposer('*', MyViewComposer::class)
//            ->sharesDataWithAllViews('downloads', 3)
//            ->hasTranslations()
            ->hasAssets();
        //            ->publishesServiceProvider('MyProviderName')
        //            ->hasRoute('web')
        //            ->hasMigration('create_package_tables')
        //            ->hasCommand(YourCoolPackageCommand::class)
        //            ->hasInstallCommand(function(InstallCommand $command) {
        //                $command
        //                    ->publishConfigFile()
        //                    ->publishAssets()
        //                    ->publishMigrations()
        //                    ->copyAndRegisterServiceProviderInApp()
        //                    ->askToStarRepoOnGitHub();
        //            });
    }

    public function packageRegistered(): void
    {
        /* Register Facades */
        foreach (self::FACADES as $abstract => $concrete) {
            $this->app->bind(containerInstanceName($abstract, 'entity'), $concrete);
        }
    }
}
