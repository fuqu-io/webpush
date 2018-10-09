<?php

namespace NotificationChannels\WebPush;

class AbstractSafariChannel{
	/**
	 * Send the given notification.
	 *
	 * @param  mixed                                  $notifiable
	 * @param  \Illuminate\Notifications\Notification $notification
	 *
	 * @return void
	 */
	public static function send($notifiable, $notification, $subscription){
		$deviceToken = $subscription->safari_device_token;

		// @todo tarek: fix this fucking garbage code
		$payload = $notification->toSafari($notifiable, $notification);


		$payload = json_encode($payload);

		$apnsHost = 'gateway.push.apple.com';
		$apnsPort = 2195;
		$apnsCert = storage_path('app/safari_cert.pem');

		$streamContext = stream_context_create();
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);

		$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 20, STREAM_CLIENT_CONNECT, $streamContext);

		$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
		fwrite($apns, $apnsMessage);

		@socket_close($apns);
		@fclose($apns);

		if(!empty($errorString)){
			\Log::debug($errorString);
		}

	}
}