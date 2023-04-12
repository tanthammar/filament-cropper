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

            \Filament\Support\Facades\FilamentAsset::register([
                Css::make('cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css'),
                Js::make('cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js'),

                Css::make('filament-cropper-css', __DIR__ . '/../resources/dist/css/filament-cropper.css'),
                AlpineComponent::make('filament-cropper', __DIR__ . '/../resources/dist/js/filament-cropper.js'),

            ], 'nuhel/filament-cropper');

        });
    }

}
