<?php
use Thujohn\Twitter\Facades\Twitter;

class TwitterHelper {	
	public static function validateAuthData($params)
	{
		$access_token = explode('-', $params['access_token']);
		$owner_id = $access_token[0];
		
		Twitter::reconfig([
			'consumer_key' => $params['consumer_key'],
			'consumer_secret' => $params['consumer_secret'],
			'token' => $params['access_token'],
			'secret' => $params['access_token_secret'],
		]);
		
		try {
			return Twitter::getUsersLookup(['user_id' => $owner_id, 'format' => 'json']);
		} catch (\Exception $ex)
		{
			return false;
		}
	}
	
	public static function getTweet($id)
	{
		$twitter = Twitters::where('user_id', Auth::id())->first();
		
		if ($twitter)
		{
			Twitter::reconfig([
				'consumer_key' => $twitter->consumer_key,
				'consumer_secret' => $twitter->consumer_secret,
				'token' => $twitter->access_token,
				'secret' => $twitter->access_token_secret,
			]);

			try {
				$tweet = json_decode(Twitter::getTweet($id, ['format' => 'json']), true);
				
				$result = [];
				
				if (isset($tweet['extended_entities']['media'][0]['video_info']['variants'][0]['url']))
				{
					$result['video'] = $tweet['extended_entities']['media'][0]['video_info']['variants'][0]['url'];
				} elseif (isset($tweet['extended_entities']['media'][0]['media_url']))
				{
					$result['photo'] = $tweet['extended_entities']['media'][0]['media_url'];
				} elseif (isset($tweet['entities']['urls'][0]['expanded_url']))
				{
					$result['link'] = $tweet['entities']['urls'][0]['expanded_url'];
				} elseif (isset($tweet['text']))
				{
					$result['status'] = $tweet['text'];
				}
				
				return empty($result) ? 'error|Could not get data for that Tweet.' : $result;
			} catch (\Exception $e) {
				return 'error|' . $e->getMessage();
			}
		}
		
		return 'error|You have no valid Twitter account.';
	}
}