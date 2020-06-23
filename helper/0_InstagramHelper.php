<?php
class InstagramHelper {

	public static function getInstance($username, $password)
	{
		$key = 'instagram_' . $username;

		try {
			$instagram = new \Instagram\Instagram();
			
			if (Session::has($key))
			{
				$instagram->initFromSavedSession(Session::get($key));
			} else {
				$instagram->login($username, $password);
			}
			
			if ($instagram->isLoggedIn())
			{
				Session::put($key, $instagram->saveSession());
				
				return $instagram;
			}
		} catch(Exception $e){
			//return $e->getMessage();
		}

		return false;
	}
	
	public static function getFeed($id)
	{
		$instagram_user = Instagrams::where('user_id', Auth::id())->first();
		
		if ($instagram_user)
		{
			$instagram = InstagramHelper::getInstance($instagram_user->username, $instagram_user->password);
			
			if ($instagram)
			{
				try {
					$feedItem = $instagram->getMediaInfo($id)->getItems()[0];

					$result = [];
					
					if (isset($feedItem->getVideoVersions()[0]))
					{
						$result['video'] = $feedItem->getVideoVersions()[0]->getUrl();
					} else
					{
						$result['photo'] = $feedItem->getImageVersions2()->getCandidates()[0]->getUrl();
					}
					
					return empty($result) ? 'error|Could not get data for that Feed.' : $result;
				} catch(Exception $e){
					return 'error|' . $e->getMessage();
				}
			} 
			
			return 'error|Could not login with your Instagram account.';
		}
		
		return 'error|You have no valid Instagram account.';
	}
}