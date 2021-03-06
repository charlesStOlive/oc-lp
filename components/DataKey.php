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
        return [];
    }

    public function onRun()
    {
        $this->addJs('assets/js/waiter.js');

        $key = $this->param('key');
        $source = SourceLog::where('key', $key)->first();

        // $user = BackendAuth::getUser();
        // if (!$user) {
        //     $source->increment('visites');
        // }

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

        $this->page['dataKey'] = $source->send_targeteable;
    }
}
