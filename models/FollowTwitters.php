<?php

class FollowTwitters extends \Eloquent {
	public $table = 'follow_twitters';
	protected $fillable = ['user_id','owner_id','name','screen_name','cover_image', 'profile_image', 'description'];
}