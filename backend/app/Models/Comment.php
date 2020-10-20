<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public function post(){
        return $this->belongsTo('App\Models\Post', 'post_id', 'post_id');
    }
    public function account(){
        return $this->belongsTo('App\Models\Account', 'user_name', 'user_name');
    }
    public function commentVote(){
        return $this->hasMany('App\Models\CommentVote', 'comment_id', 'comment_id');
    }
}
