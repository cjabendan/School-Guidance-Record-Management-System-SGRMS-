<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['key', 'name', 'role', 'enabled'];

    public static function byRole($role)
    {
        return static::where('role', $role)->get();
    }

    public static function isEnabled($key, $role = null)
    {
        $role = $role ?? auth()->user()->role;
        return static::where('key', $key)
            ->where('role', $role)
            ->value('enabled') === 1;
    }


    public static function toggle($key, $status)
    {
        static::updateOrCreate(['key' => $key], ['enabled' => $status]);
        cache()->forget("feature_{$key}");
    }
}
