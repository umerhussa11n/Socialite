<?php

class Settings extends \Eloquent {
	public $table = 'settings';
	protected $fillable = ['name','value','created_at','updated_at'];
}