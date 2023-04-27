<?php

namespace Nuhel\FilamentCropper;


use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\AssetManager;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCropperServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-cropper')
            ->hasAssets()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->resolving(AssetManager::class, function () {
            /*if (app()->runningInConsole()) {
                \Filament\Support\Facades\FilamentAsset::register([
                    //These are loaded in the blade file with custom alpine dirctive x-load
                    Css::make('cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css'),
                    Js::make('cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js'),

                    Css::make('filament-cropper-css', __DIR__ . '/../resources/dist/css/filament-cropper.css'),

                ], 'tanthammar/filament-cropper');
            }*/

            \Filament\Support\Facades\FilamentAsset::register([
                //These are loaded in the blade file with custom alpine dirctive x-load
                //Css::make('cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css'),
                //Js::make('cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js'),

                //Css::make('filament-cropper-css', __DIR__ . '/../resources/dist/css/filament-cropper.css'),
                AlpineComponent::make('filament-cropper', __DIR__ . '/../resources/dist/FilamentCropper.js'),

            ], 'tanthammar/filament-cropper');

        });
    }

    /** commands
     * first compile the js and css, then publish the assets with Spatie's package tools
     npm run dev or prod
     php artisan vendor:publish --tag=filament-cropper-assets --force
     */

}
