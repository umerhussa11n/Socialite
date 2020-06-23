<?php

class Twitters extends \Eloquent {	
	protected $table = 'twitters';
	
	protected $fillable = ['owner_id', 'user_id','consumer_key','consumer_key_secret','access_token','access_token_recset','name','screen_name','cover_image','profile_image','description'];
}