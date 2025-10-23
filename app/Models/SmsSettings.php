<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    protected $table = 'sms_settings';
    
    protected $fillable = [
        'sms_enabled',
    ];
    
    protected $casts = [
        'sms_enabled' => 'boolean',
    ];
    
    /**
     * Get the value of a setting
     */
    public static function getValue(string $key): bool
    {
        $setting = self::first();
        
        if (!$setting) {
            // If no record exists, create one with default values
            $setting = self::create([
                'sms_enabled' => true,
            ]);
        }
        
        return (bool) $setting->$key;
    }
    
    /**
     * Update a setting value
     */
    public static function setValue(string $key, bool $value): void
    {
        $setting = self::first();
        
        if (!$setting) {
            $setting = self::create([
                'sms_enabled' => true,
            ]);
        }
        
        $setting->$key = $value;
        $setting->save();
    }
}
