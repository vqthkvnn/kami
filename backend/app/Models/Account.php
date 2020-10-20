<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    private $key = 'user_name';
    protected $table = "account";

    public function comment(){
        return $this -> hasMany('App\Models\Comment', 'user_name', 'user_name');
    }
    public function commentVote(){
        return $this -> hasMany('App\Models\CommentVote', $this->key, $this->key);
    }
    public function notification(){
        return $this -> hasMany('App\Models\Notification', $this -> key, $this -> key);
    }
    public function post(){
        return $this->hasMany('App\Models\Post', $this->key, $this->key);
    }
    public function postVote(){
        return $this->hasMany('App\Models\PostVote', $this->key, $this->key);
    }
}
