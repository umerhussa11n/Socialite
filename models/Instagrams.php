<?php

class Instagrams extends \Eloquent {	
	protected $table = 'instagrams';
	
	protected $fillable = ['user_id','username','password','profile_image','owner_id','full_name'];
}