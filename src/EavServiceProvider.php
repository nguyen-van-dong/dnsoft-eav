<?php

namespace Dnsoft\Eav;

use Dnsoft\Core\Events\CoreAdminMenuRegistered;
use Dnsoft\Eav\Events\EavAdminMenuRegistered;
use Dnsoft\Eav\Models\Attribute;
use Dnsoft\Eav\Repositories\AttributeRepository;
use Dnsoft\Eav\Repositories\AttributeRepositoryInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Module\Customer\Events\CustomerAdminMenuRegistered;

class EavServiceProvider extends ServiceProvider
{
    public function register()
    {
        //$this->app->singleton('rinvex.attributes.attribute', Attribute::class);

        $this->app->singleton(AttributeRepositoryInterface::class, function () {
            return new AttributeRepository(new Attribute());
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eav');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'eav');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/eav'),
        ], 'dnsoft-admin');

        Blade::include('eav::form.attributes', 'attributes');

//        Attribute::typeMap([
//            self::TEXT     => \Rinvex\Attributes\Models\Type\Text::class,
//            self::BOOLEAN  => \Rinvex\Attributes\Models\Type\Boolean::class,
//            self::INTEGER  => \Rinvex\Attributes\Models\Type\Integer::class,
//            self::VARCHAR  => \Rinvex\Attributes\Models\Type\Varchar::class,
//            self::DATETIME => \Rinvex\Attributes\Models\Type\Datetime::class,
//            self::IMAGE    => \Newnet\Eav\Models\Type\Image::class,
//        ]);

        Event::listen(CoreAdminMenuRegistered::class, function ($menu) {

            $menu->add('Customer', ['id' => 'customer'])->data('order', 2000)->prepend('<i class="fas fa-users"></i>');
            $menu->add('Customer', ['route' => 'customer.admin.customer.index', 'parent' => $menu->customer->id]);

            event(new EavAdminMenuRegistered(), $menu);
        });
    }

}