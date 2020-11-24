<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    protected $table = "comment_vote";
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }

    protected $fillable = [
        'user_name',
        'comment_id',
    ];
}
