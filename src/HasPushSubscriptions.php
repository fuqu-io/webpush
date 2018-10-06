<?php

namespace NotificationChannels\WebPush;

trait HasPushSubscriptions{
	/**
	 * Get the user's subscriptions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function PushSubscriptions(){
		return $this->morphMany(PushSubscription::class, 'pushable');
	}

	/**
	 * @deprecated  Seems like it's only used by tests.
	 *
	 * Update (or create) user subscription.
	 *
	 * @param  string      $endpoint
	 * @param  string|null $key
	 * @param  string|null $token
	 *
	 * @return \NotificationChannels\WebPush\PushSubscription
	 */
//    public function updatePushSubscription($endpoint, $key = null, $token = null)
//    {
//        $subscription = PushSubscription::findByEndpoint($endpoint);
//
//        if ($subscription) {
//            $subscription->public_key = $key;
//            $subscription->auth_token = $token;
//            $subscription->save();
//
//            return $subscription;
//        }
//
//        return $this->pushSubscriptions()->save(new PushSubscription([
//            'endpoint' => $endpoint,
//            'public_key' => $key,
//            'auth_token' => $token,
//        ]));
//    }

	/**
	 * Determine if the given subscription belongs to this user.
	 *
	 * @param  \NotificationChannels\WebPush\PushSubscription $subscription
	 *
	 * @return bool
	 */
//    public function pushSubscriptionBelongsToUser($subscription)
//    {
//        return (int) $subscription->user_id === (int) $this->getAuthIdentifier();
//    }

	/**
	 * Delete subscription by endpoint.
	 *
	 * @param  string $endpoint
	 *
	 * @return void
	 */
	public function deletePushSubscription($endpoint){
		$this->pushSubscriptions()
		     ->where('endpoint', $endpoint)
		     ->delete();
	}

	/**
	 * Get all subscriptions.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function routeNotificationForWebPush(){
		return $this->pushSubscriptions;
	}
}
