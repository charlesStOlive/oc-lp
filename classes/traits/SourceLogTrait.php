<?php namespace Waka\Lp\Classes\Traits;

use \Waka\Informer\Models\Inform;

trait SourceLogTrait
{
    /*
     * Constructor
     */
    public static function bootSendsTrait()
    {
        static::extend(function ($model) {
            /*
             * Define relationships
             */
            $model->morphToMany['sends'] = [
                'Waka\Lp\Models\SourceLog',
                'name' => 'montageable',
                'delete' => true,
            ];

            // $model->bindEvent('model.afterSave', function () use ($model) {
            //     $model->updateCloudiRelations('attach');
            // });

            // $model->bindEvent('model.beforeDelete', function () use ($model) {
            //     $model->clouderDeleteAll();
            // });
        });
    }

    /**
     *
     */

    /**
     *
     */
}
