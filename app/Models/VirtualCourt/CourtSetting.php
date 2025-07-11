<?php

namespace App\Models\VirtualCourt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtSetting extends Model
{
    use HasFactory;

    protected $table = 'virtual_court_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Helper to get setting value by key with default.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper to set/update setting.
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text', ?array $options = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type'  => $type,
                'options'=> $options,
            ]
        );
    }
} 