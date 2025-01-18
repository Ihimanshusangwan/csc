<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configurations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Retrieve a configuration value by key.
     *
     * @param string $key
     * @return string|null
     */
    public static function getValue($key)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : null;
    }

    /**
     * Set or update a configuration value by key.
     *
     * @param string $key
     * @param string $value
     * @param string|null $description
     * @return void
     */
    public static function setValue($key, $value, $description = null)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }
}
