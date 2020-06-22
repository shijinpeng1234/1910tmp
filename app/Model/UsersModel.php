<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'p_users';
    protected  $primaryKey = 'user_id';
    public $timestamps=false;
}
