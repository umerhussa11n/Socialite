<?php

class Follow extends \Eloquent {
	public $table = 'follow_facebooks';
	protected $fillable = ['name','page_id','description','cover','user_id'];
}