<?php

class FbAccounts extends \Eloquent {
	public $table = 'fb_accounts';
	protected $fillable = ['user_id','app_id','app_secret','created_at','updated_at'];
}
