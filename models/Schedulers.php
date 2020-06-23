<?php

class Schedulers extends \Eloquent {	
	protected $table = 'schedulers';
        protected $fillable = ['scheduled_', 'media_type_', 'post_type_', 'status_', 'sch_time_'];
}
