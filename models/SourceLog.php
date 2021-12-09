<?php namespace Waka\Lp\Models;

use Carbon\Carbon;
use Model;

/**
 * DatasourceLog Model
 */
class SourceLog extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_lp_source_logs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [''];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['*'];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = ['EndKeyAtZone'];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['datas'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = ['lp_url'];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = ['created_at', 'updated_at', 'sourceable_id', 'sourceable_type', 'target_id', 'target_type'];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'end_key_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsToMany = [];
    public $morphTo = [
        'sendeable' => [],
        'send_targeteable' => [],
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getValideAttribute()
    {
        if ($this->user_delete_key) {
            return false;
        }
        if ($this->end_key_at < Carbon::now()) {
            return false;
        } else {
            return true;
        }
    }

    public function getEndKeyAtZoneAttribute()
    {
        $backendTimeZone = \Backend\Models\Preference::get('timezone');
        //trace_log($backendTimeZone);
        $val = $this->end_key_at->setTimezone($backendTimeZone);
        return $val;
    }

    public function getLpUrlAttribute() {
        return url('lp/'.$this->landing_page.'/'.$this->key);
    }
}
