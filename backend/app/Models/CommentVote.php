<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    protected $table = "comment_vote";
    public function account(){
        return $this->belongsTo('App\Models\Account', 'user_name', 'user_name');
    }
    public function comment(){
        return $this->belongsTo('App\Models\Comment', 'comment_id', 'comment_id');
    }
    public function post(){
        return $this->belongsTo('App\Models\Post', 'post_id', 'post_id');
    }
}
