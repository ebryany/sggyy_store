<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Based on migration: 2024_01_01_000007_create_settings_table
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'updated_by',
    ];

    /**
     * Get setting value by key with type casting
     * Called by: SettingsService@get (line 14)
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if not found
     * @return mixed Casted value based on type
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Cast value based on type field
        return match($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set setting value with auto type detection
     * Called by: SettingsService@set (line 20)
     * 
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @param string $type Value type (text, boolean, integer, float, json)
     * @param int|null $updatedBy User ID who updated
     * @return void
     */
    public static function setValue(string $key, $value, string $type = 'text', ?int $updatedBy = null): void
    {
        // Auto-detect and convert value to string for storage
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
            $type = 'boolean';
        }

        // Update or create setting
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_by' => $updatedBy,
            ]
        );
    }

    /**
     * Relationship: User who last updated this setting
     * Based on migration foreignKey: updated_by
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
