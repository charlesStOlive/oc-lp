<?php namespace Waka\Lp\Components;

use Cms\Classes\ComponentBase;

class GestionKey extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'gestionKey Component',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [
            'btn_color' => [
                'title' => 'Couleur',
                'description' => 'Couleur du bouton',
                'default' => null,
                'type' => 'string',
            ],
        ];
    }

    public function onRemoveKey()
    {
        $key = $this->param('key');
        $source = \Waka\Lp\Models\SourceLog::where('key', $key)->first();
        $source->user_delete_key = true;
        $source->save();
        return \Redirect::to('/lp/deleted_cod');
    }
}
