<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue($key, $default = null)
    {
        return cache()->remember("system_setting_{$key}", 300, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function setValue($key, $value)
    {
        cache()->forget("system_setting_{$key}");
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
