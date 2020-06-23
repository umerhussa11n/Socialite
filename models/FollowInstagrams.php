<?php

class FollowInstagrams extends \Eloquent {
	public $table = 'follow_instagrams';
	protected $fillable = ['user_id','owner_id','full_name','username','profile_image'];
}