<?php namespace Waka\Lp\Components;

use BackendAuth;
use Cms\Classes\ComponentBase;
use Redirect;
use Waka\Lp\Models\SourceLog;

class DataKey extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'UserKey Component',
            'description' => "Permet d'ifentifer un utilisateur",
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

    public function onRun()
    {
        $this->addJs('assets/js/waiter.js');
        $dataFromKey = $this->getKeyData();
        
        $this->page['dataClientKey'] = $dataFromKey['key'];
        $this->page['dataKey'] = $dataFromKey['dataKey'];

    }

    public function getKeyData() {
        $key = $this->param('key');
        $source = SourceLog::where('key', $key)->first();
        //
        if (!$source) {
            return Redirect::to('/lp/bad_cod');
        }
        //
        if (!$source->valide) {
            return Redirect::to('/lp/deleted_cod');
        }
        $b_user = BackendAuth::getUser();
        if (!$b_user) {
            $source->visites = $source->visites + 1;
            $source->save();
        }
        return [
            'key' => $key,
            'dataKey' => $source->send_targeteable,
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
