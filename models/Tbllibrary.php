<?php

class Tbllibrary extends \Eloquent {	
	protected $table = 'tbl_library';
    protected $fillable = ['id', 'title'];
    public $timestamps = false;
}