<?php

namespace App\Models;

use Eloquent as Model;

class User extends Model
{    
    public $table = 'users';   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
		
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required'
    ];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     **/
    public function profile() {
        return $this->hasOne('App\Models\Profile');
    }
}
