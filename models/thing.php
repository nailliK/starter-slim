<?php
namespace App;

class Thing extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'things';
    public $timestamps = true;
	
	public function user() {
		return $this->belongsTo('\App\User');
	}
}