<?php namespace Waka\Lp;

use Backend;
use Event;
use Lang;
use System\Classes\PluginBase;
use Waka\Mailer\Controllers\WakaMails as WakaMailsController;
use Waka\Mailer\Models\WakaMail as WakaMailModel;
use Waka\Mailtoer\Controllers\WakaMailTos as WakaMailTosController;
use Waka\Mailtoer\Models\WakaMailto as WakaMailtoModel;

/**
 * Lp Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Lp',
            'description' => 'No description provided yet...',
            'author' => 'Waka',
            'icon' => 'icon-leaf',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        WakaMailtoModel::extend(function ($model) {
            $model->morphMany['sends'] = ['Waka\Lp\Models\SourceLog', 'name' => 'sendeable'];
        });
        WakaMailModel::extend(function ($model) {
            $model->morphMany['sends'] = ['Waka\Lp\Models\SourceLog', 'name' => 'sendeable'];
        });

        Event::listen('backend.form.extendFields', function ($widget) {

            // Only for the User controller
            if ($widget->getController() instanceof WakaMailsController || $widget->getController() instanceof WakaMailTosController) {
                if ($widget->model instanceof WakaMailModel || $widget->model instanceof WakaMailtoModel) {
                    $opt = \Config::get('waka.lp::durations');
                    if ($widget->isNested === true) {
                        return;
                    }
                    //On empeche l'affichage pour les sous widget des popups behaviors
                    if ($widget->alias == 'mailBehaviorformWidget') {
                        return;
                    }
                    if ($widget->alias == 'mailDataformWidget') {
                        return;
                    }
                    $widget->addTabFields([
                        'use_key' => [
                            'label' => Lang::get('waka.lp::lang.source_log.use_key'),
                            'tab' => 'waka.lp::lang.source_log.tab_lp',
                            'span' => 'auto',
                            'type' => 'checkbox',
                        ],
                        'key_duration' => [
                            'label' => Lang::get('waka.lp::lang.source_log.duration'),
                            'tab' => 'waka.lp::lang.source_log.tab_lp',
                            'span' => 'auto',
                            'type' => 'dropdown',
                            'options' => $opt,
                            'default' => '1w',
                        ],
                    ]);
                }

            }
        });

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Lp\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'waka.lp.some_permission' => [
                'tab' => 'Lp',
                'label' => 'Some permission',
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'lp' => [
                'label' => 'Lp',
                'url' => Backend::url('waka/lp/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['waka.lp.*'],
                'order' => 500,
            ],
        ];
    }
}
