<?php namespace Waka\Lp\Classes;

use Carbon\Carbon;
use Waka\Lp\Models\SourceLog;
use Waka\Utils\Classes\DataSource;

class LogKey
{
    private $key;
    public $modelId;
    public $productor;
    public $log;
    public $landingPage;

    public function __construct($productor, $modelId)
    {
        //Si création manuel d'un log key on instancie le model.
        $this->productor = $productor;
        $this->modelId = $modelId;
        //
        if(!$this->modelId) {
            throw new SystemException('Le modèle ID est inconnu lors de la création du logKey');
        }
        //
        $existingLoadingKey = $this->existe();
        if ($existingLoadingKey) {
            $this->key = $existingLoadingKey->id;
        } else {
            $this->key = uniqid() . str_Random(8);
        }
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getLandingPage()
    {
        return $this->log->landingPage;
    }

    public function getLogKey()
    {
        return $this->log->key;
    }

    public function existe()
    {
        
        $ds = \DataSources::find($this->productor->data_source);
        $targeteable = $this->checkMorphMap($ds->class, true);
        return SourceLog::where('send_targeteable_id', $this->modelId)
            ->where('send_targeteable_type', $targeteable)//todoeable
            ->where('sendeable_type', get_class($this->productor))
            ->where('sendeable_id', $this->productor->id)
            ->where('landing_page', $this->productor->lp_data->url)
            ->where('user_delete_key', false)->get()->first();
    }

    public function add($datas = [])
    {
        $existingLoadingKey = $this->existe();
        //
        if ($existingLoadingKey) {
            $log = $existingLoadingKey;
            $log->end_key_at = $this->getEndKeyAt($this->productor->lp_data->key_duration);
            $log->landing_page = $this->productor->lp_data->url;
            $log->save();
            $this->log = $log;
        } else {
            //trace_log("clef a créer");
            $ds = \DataSources::find($this->productor->data_source);
            $log = new \Waka\Lp\Models\SourceLog();
            $log->key = $this->key;
            $log->send_targeteable_id = $this->modelId;
            $log->send_targeteable_type = $this->checkMorphMap($ds->class, true); //todotargeteable
            $log->landing_page = $this->productor->lp_data->url;
            $log->datas = $datas;
            $log->end_key_at = $this->getEndKeyAt($this->productor->lp_data->key_duration);
            $this->productor->sends()->add($log);
            $this->log = $log;
        }
    }

    public function getEndKeyAt($duration)
    {

        $date = Carbon::now();
        //trace_log($date);
        switch ($duration) {
            case '5m':
                return $date->addMinutes(5)->toDateTimeString();
                break;
            case '30m':
                return $date->addMinutes(30)->toDateTimeString();
                break;
            case '1h':
                return $date->addHour()->toDateTimeString();
                break;
            case '24h':
                return $date->addDay()->toDateTimeString();
                break;
            case '1w':
                return $date->addWeek()->toDateTimeString();
                break;
            case '1Mo':
                return $date->addMonth()->toDateTimeString();
                break;
            case '1t':
                return $date->addMonth(3)->toDateTimeString();
                break;
        }
    }

    public function checkMorphMap($className, $name = false) {
        if(!$className) return;
        if (substr($className, 0, 1) === "\\") {
            $className = substr($className, 1);
        }
        $morphClassMaps = \Winter\Storm\Database\Relations\Relation::morphMap();
        foreach($morphClassMaps as $morphName=>$morphClass) {
            // trace_log($morphClass ."  ==  ".$className."  ==  ".$morphName);
            if($morphClass ==  $className)  {
                return $name ? $morphName : $morphClass;
            } else if($morphName ==  $className)  {
                return $name ? $morphName : $morphClass;
            } 
           
        }
         return $className;
    }
}
