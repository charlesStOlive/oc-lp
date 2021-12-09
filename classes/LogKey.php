<?php namespace Waka\Lp\Classes;

use Carbon\Carbon;
use Waka\Lp\Models\SourceLog;
use Waka\Utils\Classes\DataSource;

class LogKey
{
    private $key;
    public $modelId;
    public $prodModel;
    public $log;
    public $landingPage;

    public function __construct($productorModel, $modelId = null)
    {
        if(!$modelId) {
            //modelId par defaut est envoyé par les class productrices (Maier, Pdfer)
            $this->modelId = $productorModel->modelId;
            $this->prodModel = $productorModel->getProductor();
        } else {
            //Si création manuel d'un log key on instancie le model.
            $this->modelId = $modelId;
            $this->prodModel = $productorModel;
            //On triche ici plutot que d'utilisr une class productrice on prend le modèle productor wakaMail plutot que Mailcreator...
        }
        if(!$this->modelId) {
            throw new SystemException('Le modèle ID est inconnu lors de la création du logKey');
        }
        
        
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
        $ds = \DataSources::find($this->prodModel->data_source);
        return SourceLog::where('send_targeteable_id', $this->modelId)
            ->where('send_targeteable_type', $ds->class)
            ->where('sendeable_type', get_class($this->prodModel))
            ->where('sendeable_id', $this->prodModel->id)
            ->where('landing_page', $this->prodModel->lp)
            ->where('user_delete_key', false)->get()->first();
    }

    public function add($datas = [])
    {
        //trace_log("add fonction");
        //trace_log($this->prodModel->data_source);
        //trace_log($this->key);
        //trace_log($datas);
        //trace_log($this->getEndKeyAt($this->prodModel->key_duration));


        $existingLoadingKey = $this->existe();
        
        if ($existingLoadingKey) {
           //trace_log($existingLoadingKey->toArray());
            $log = $existingLoadingKey;
            $log->end_key_at = $this->getEndKeyAt($this->prodModel->key_duration);
            $log->landing_page = $this->prodModel->lp;
            $log->save();
            $this->log = $log;
        } else {
            $ds = \DataSources::find($this->prodModel->data_source);
            $log = new \Waka\Lp\Models\SourceLog();
            $log->key = $this->key;
            $log->send_targeteable_id = $this->modelId;
            $log->send_targeteable_type = $ds->class;
            $log->landing_page = $this->prodModel->lp;
            $log->datas = $datas;
            $log->end_key_at = $this->getEndKeyAt($this->prodModel->key_duration);
            $this->prodModel->sends()->add($log);
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
        }
    }
}
