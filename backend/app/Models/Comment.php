<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = false;
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }

    public function commentVote()
    {
        return $this->hasMany(CommentVote::class, 'comment_id', 'comment_id');
    }
    public function commentContent(){
        return $this->hasOne(CommentContent::class, 'comment_id','comment_id')
            ->orderBy('comment_content_id');
    }

    protected $fillable = [
        'comment_id',
        'post_id',
        'comment_status',
        'user_name',
        'comment_quote_post',
        'comment_quote_comment',
        'comment_date_delete',
        'comment_note',
    ];
}
