<?php

class Pages extends \Eloquent {	
	public $table = 'facebooks';
	protected $fillable = ['id','name','access_token','page_id','perms','description','cover','user_id'];
}