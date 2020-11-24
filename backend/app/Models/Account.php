<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
//    private $key = 'user_email';
    protected $table = "account";
    protected $primaryKey = 'user_name';
    public $timestamps = false;

    public function comment()
    {
        return $this->hasMany(Comment::class, $this->key, $this->key);
    }

    public function commentVote()
    {
        return $this->hasMany(CommentVote::class, $this->key, $this->key);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, $this->key, $this->key);
    }

    public function post()
    {
        return $this->hasMany(Post::class, $this->key, $this->key);
    }

    public function postVote()
    {
        return $this->hasMany(PostVote::class, $this->key, $this->key);
    }

    public function postContent()
    {
        return $this->hasMany(CommentContent::class, 'user_name', 'user_name');
    }

    protected $fillable = [
        'user_name',
        'user_pass',
        'user_status',
        'user_permission',
        'user_full_name',
        'user_birth',
        'user_avatar',
        'user_date_create',
        'user_note',
    ];
}
