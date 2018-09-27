<?php

namespace NotificationChannels\WebPush;

use Minishlink\WebPush\WebPush;
use Illuminate\Notifications\Notification;

class WebPushChannel{
	/** @var \Minishlink\WebPush\WebPush */
	protected $webPush;

	/**
	 * @param  \Minishlink\WebPush\WebPush $webPush
	 *
	 * @return void
	 */
	public function __construct(WebPush $webPush){
		$this->webPush = $webPush;
	}

	/**
	 * Send the given notification.
	 *
	 * @param  mixed                                  $notifiable
	 * @param  \Illuminate\Notifications\Notification $notification
	 *
	 * @return void
	 */
	public function send($notifiable, Notification $notification){
		$subscriptions = $notifiable->routeNotificationFor('WebPush');

		if(!$subscriptions || $subscriptions->isEmpty()){
			return;
		}

		$safari_subscriptions = $subscriptions->where('public_key', 'safari');
		$subscriptions        = $subscriptions->where('public_key', '!=', 'safari');

		$payload = json_encode($notification->toWebPush($notifiable, $notification)->toArray());
		$subscriptions->each(function ($sub) use ($payload){
			$this->webPush->sendNotification(
				$sub->endpoint,
				$payload,
				$sub->public_key,
				$sub->auth_token,
				true //
			);
		});
//		$response = $this->webPush->flush();
//
//		$this->deleteInvalidSubscriptions($response, $subscriptions);

		$safari_subscriptions->each(function ($subscription) use ($notifiable, $notification){
			AbstractSafariChannel::send($notifiable, $notification, $subscription);
		});
	}

	/**
	 * @param  array|bool                               $response
	 * @param  \Illuminate\Database\Eloquent\Collection $subscriptions
	 *
	 * @return void
	 */
	protected function deleteInvalidSubscriptions($response, $subscriptions){
		if(!is_array($response)){
			return;
		}

		foreach($response as $index => $value){
			if(!$value['success'] && isset($subscriptions[$index])){
				\FuquIo\LaravelCore\Debug::log(json_encode($value));
				$subscriptions[$index]->delete();
			}
		}
	}
}
