<?php

namespace Modules\Crm\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrmSetting extends Model
{
    use HasFactory;

    protected $table = 'crm_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->value;
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $group
     * @return void
     */
    public static function set($key, $value, $group = null)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        
        if ($group) {
            $setting->group = $group;
        }
        
        $setting->save();
        
        return $setting;
    }

    /**
     * Get all settings for a specific group.
     *
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public static function getGroup($group)
    {
        return static::where('group', $group)->get()->pluck('value', 'key');
    }

    /**
     * Determine if a setting exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Delete a setting.
     *
     * @param string $key
     * @return bool
     */
    public static function remove($key)
    {
        return static::where('key', $key)->delete();
    }
}
