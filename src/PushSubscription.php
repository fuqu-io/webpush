<?php

namespace NotificationChannels\WebPush;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushSubscription extends Model
{
	use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
    ];

    public function Pushable(){
    	return $this->morphTo();
    }

    /**
     * Find a subscription by the given endpint.
     *
     * @param  string $endpoint
     * @return static|null
     */
    public static function findByEndpoint($endpoint)
    {
        return static::where('endpoint', $endpoint)->first();
    }

}
