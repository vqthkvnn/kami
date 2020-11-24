<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = false;
    protected $primaryKey='comment_id';
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_name', 'user_name')
            ;
    }
    public function accountShort()
    {
        return $this->hasOne(Account::class, 'user_name', 'user_name')
            ->select(['user_name', 'user_avatar', 'user_permission']);
    }

    public function commentVote()
    {
        return $this->hasMany(CommentVote::class, 'comment_id', 'comment_id');
    }
    public function commentContent(){
        return $this->hasOne(CommentContent::class, 'comment_id','comment_id')
            ->orderBy('comment_content_id')
            ->select('comment_id', 'comment_content_id',
                'comment_content_main','comment_content_create');
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
