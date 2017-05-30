<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;

class Setting extends AbstractModel
{
    protected $editableFields = [
        'option_key',
        'option_value',
        'display_name',
        'type',
        'order',
        'details',
        'group_id'
    ];
    protected $table = 'settings';


    public function __construct()
    {
        parent::__construct();
    }


    public function settingGroup()
    {
        return $this->belongsTo('App\Models\SettingGroup');
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $primaryKey = 'id';

    public static function getAllSettings()
    {
        $result = [];
        $settings = static::get();
        if ($settings) {
            foreach ($settings as $key => $row) {
                $result[$row->option_key] = $row->option_value;
            }
        }
        return $result;
    }

    public static function getSetting($settingKey)
    {
        return static::getBy(['option_key' => $settingKey]);
    }

    public static function updateSettings($data = [])
    {
        $result = [
            'error' => false,
            'response_code' => 200,
            'errors' => [],
            'message' => 'Done but some error occurred.',
        ];
        foreach ($data as $key => $row) {
            $setting = static::getSetting($key);
            if ($setting) {
                $setting->option_value = $row;
            } else {
                $setting = new static;
                $setting->option_key = $key;
                $setting->option_value = $row;
            }
            if (!$setting->save()) {
                $result['error'] = true;
                $result['errors'][] = $key;
            }
        }
        if (sizeof($result['errors']) == sizeof($data)) {
            $result['message'] = 'Cannot update settings. Please try again or contact administrator';
            $result['response_code'] = 500;
        }
        if (sizeof($result['errors']) < 1) {
            $result['message'] = 'Your settings has been updated';
        }
        return $result;
    }


    public function createItem($data, $justUpdateSomeFields = true)
    {
        $data['id'] = 0;
        $result = $this->fastEdit($data, true, $justUpdateSomeFields);
        return $result;
    }

}
