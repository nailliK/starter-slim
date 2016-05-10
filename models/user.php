<?php
namespace App;

class User extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'users';
	public $timestamps = true;

	public function things() {
		return $this->hasMany('\App\Thing');
	}
}