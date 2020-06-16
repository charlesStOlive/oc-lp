<?php namespace Waka\Lp\Classes;

use Carbon\Carbon;
use Waka\Lp\Models\SourceLog;

class LogKey
{
    private $key;
    public $dataSourceId;
    public $prodModel;
    public $log;

    public function __construct($dataSourceId, $prodModel)
    {
        $this->dataSourceId = $dataSourceId;
        $this->prodModel = $prodModel;
        $logKeyExiste = $this->existe();
        if ($logKeyExiste) {
            $this->key = $logKeyExiste->id;
        } else {
            $this->key = uniqid() . str_Random(8);
        }

    }

    public function getKey()
    {
        return $this->key;
    }

    public function existe()
    {
        return SourceLog::where('send_targeteable_id', $this->dataSourceId)
            ->where('send_targeteable_type', $this->prodModel->data_source->modelClass)
            ->where('sendeable_type', get_class($this->prodModel))
            ->where('sendeable_id', $this->prodModel->id)
            ->where('user_delete_key', false)->get()->first();

    }

    public function add($datas = [])
    {
        $logKeyExiste = $this->existe();
        if ($logKeyExiste) {
            $log = $logKeyExiste;
            $log->end_key_at = $this->getEndKeyAt($this->prodModel->key_duration);
            $log->save();
            $this->log = $log;
        } else {
            $log = new \Waka\Lp\Models\SourceLog();
            $log->key = $this->key;
            $log->send_targeteable_id = $this->dataSourceId;
            $log->send_targeteable_type = $this->prodModel->data_source->modelClass;
            $log->datas = $datas;
            $log->end_key_at = $this->getEndKeyAt($this->prodModel->key_duration);
            $this->prodModel->sends()->add($log);
            $this->log = $log;
        }
    }

    public function getEndKeyAt($duration)
    {

        $date = Carbon::now();
        trace_log($date);
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
