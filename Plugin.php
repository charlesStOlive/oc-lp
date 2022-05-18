<?php namespace Waka\Lp;

use Backend;
use Event;
use Lang;
use System\Classes\PluginBase;

/**
 * Lp Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'Waka.Utils',
        'Waka.Mailer',
    ];
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
        $WakaMailsController = 'Waka\Mailer\Controllers\WakaMails';
        $WakaMailModel = 'Waka\Mailer\Models\WakaMail';
        $WakaMailTosController = 'Waka\Mailtoer\Controllers\WakaMailTos';
        $WakaMailtoModel = 'Waka\Mailtoer\Models\WakaMailto';

        if (class_exists($WakaMailModel)) {
            $WakaMailModel::extend(function ($model) {
                $model->morphMany['sends'] = ['Waka\Lp\Models\SourceLog', 'name' => 'sendeable'];
            });
        }
        if (class_exists($WakaMailtoModel)) {
            $WakaMailtoModel::extend(function ($model) {
                $model->morphMany['sends'] = ['Waka\Lp\Models\SourceLog', 'name' => 'sendeable'];
            });
        }

        Event::listen('backend.form.extendFields', function ($widget) use ($WakaMailModel, $WakaMailsController, $WakaMailtoModel, $WakaMailTosController) {

            if (class_exists($WakaMailModel)) {
                if ($widget->getController() instanceof $WakaMailsController) {
                    if ($widget->model instanceof $WakaMailModel) {
                        $this->injectController($widget);
                    }
                }
            }

            if (class_exists($WakaMailtoModel)) {
                if ($widget->getController() instanceof $WakaMailTosController) {
                    if ($widget->model instanceof $WakaMailtoModel) {
                        $this->injectController($widget);
                    }
                }
            }
        });

        //Cette evenement permet d'ajouter au données de l'email les informations sur une clef unique d'identification.
        Event::listen('waka.productor.subscribeData', function ($productorModel) {
            //trace_log('waka.productor.subscribeData');
            $productor = $productorModel->getProductor();
            $lpeableData = $productor->lp_data;
            $dsId = $productorModel->modelId;
            if(!$lpeableData) {
                return null;
            }
            if(!$lpeableData->isReady()) {
                return null;
            }
            if ($lpeableData  && $dsId) {
                $logKey = new \Waka\Lp\Classes\LogKey($productor, $dsId);
                $logKey->add();
                //trace_log($logKey->log->toArray());
                return ['log' => $logKey->log];
            } else {
                //trace_log('pas de log key');
                return null;
            }
        });
    }

    public function injectController($widget)
    {
        $opt = \Config::get('waka.lp::durations');
        if ($widget->isNested === true) {
            return;
        }
        //On empeche l'affichage pour les sous widget des popups behaviors
        if ($widget->alias == 'mailBehaviorformWidget') {
            return;
        }
        if ($widget->alias == 'myduplicateformWidget') {
            return;
        }
        if ($widget->alias == 'mailDataformWidget') {
            return;
        }
        if ($widget->alias == 'sideBarUpdateformWidget') {
            return;
        }
        if ($widget->context == 'create') {
            return;
        }
        $widget->addTabFields([
            'use_key' => [
                'label' => Lang::get('waka.lp::lang.source_log.use_key'),
                'tab' => 'waka.lp::lang.source_log.tab_lp',
                'permissions' => ['waka.mailer.admin.super'],
                'span' => 'auto',
                'type' => 'switch',
            ],
            'key_duration' => [
                'label' => Lang::get('waka.lp::lang.source_log.duration'),
                'tab' => 'waka.lp::lang.source_log.tab_lp',
                'span' => 'auto',
                'type' => 'dropdown',
                'permissions' => ['waka.mailer.admin.super'],
                'options' => $opt,
                'default' => '1w',
            ],
            'lp' => [
                'label' => Lang::get('waka.lp::lang.source_log.lp'),
                'tab' => 'waka.lp::lang.source_log.tab_lp',
                'span' => 'full',
                'permissions' => ['waka.mailer.admin.super'],
            ],
        ]);
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Waka\Lp\Components\GestionKey' => 'gestionKey',
            'Waka\Lp\Components\DataKey' => 'dataKey',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.lp.admin.super' => [
                'tab' => 'Waka - Landing Page',
                'label' => 'Super Administrateur des Landing Page',
            ],
            'waka.lp.admin.base' => [
                'tab' => 'Waka - Landing Page',
                'label' => 'Administrateur des Landing Page',
            ],
            'waka.lp.user' => [
                'tab' => 'Waka - Landing Page',
                'label' => 'Utilisateur des Landing Page',
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
