<?php 
/**
* 
*/
require_once 'Dropbox/autoload.php';
use \Dropbox as dbx;

class DropboxHelper
{
	private $client;
	private $host = 'api.dropbox.com';

	public function __construct(){
		$client_id = $this->getAccessToken();
		if (empty($client_id)) {
			return new Exception("Access Token Empty", 1);			
		}
		$this->client = new dbx\Client($client_id, "PHP-Example/1.0");		
	}
	public function getAccessToken(){
		return Auth::user()->dropbox_key;

	}
	public function listFiles($path){
		$files = $this->client->getMetadataWithChildren($path);		
		$sorted_files = $this->sortFiles($files["contents"]);
		return $sorted_files;
	}

	public function sortFiles($files){
		$final_directories = array();
		$final_files = array();
		$access_token = $this->getAccessToken();
		$video_mimes = array(
						'video/3gpp2','video/3gpp','video/x-ms-asf','video/x-msvideo',
						'video/mpeg', 'video/x-f4v', 'video/x-flv', 'video/x-m4v', 'video/mkv',
						'video/mp4', 'video/quicktime', 'video/mpeg', 'video/x-ms-wmv', 'video/x-ms-vob'
						);

		foreach ($files as $file) {
			if ($file["is_dir"]) {
				array_push($final_directories, $file);
			} else {
				if (in_array($file["mime_type"], array('image/jpeg','image/png','image/bmp','image/gif','image/tiff'))) {
					$file["image_preview"] = 'https://api-content.dropbox.com/1/thumbnails/dropbox/'.$file["path"].'?&access_token='.$access_token.'&size=m';
					array_push($final_files, $file);
				} else if(in_array($file["mime_type"], $video_mimes)){
					$file["video_preview"] = 'https://api-content.dropbox.com/1/thumbnails/dropbox/'.$file["path"].'?&access_token='.$access_token.'&size=m';
					array_push($final_files, $file);
				}
			}
		}		
		return array_merge($final_directories, $final_files);
	}

	public function getSharedUrl($path){
		return $this->client->createShareableLink($path);
	}

	public function getRawUrl($path){
		$shared_url = $this->getSharedUrl($path);
		return $shared_url.'&raw=1';
	}


}

 ?>