<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVote extends Model
{
    protected $table = "post_vote";
    public function account(){
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }
    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
    protected $fillable = [
        'user_name',
        'post_id',
    ];
}
