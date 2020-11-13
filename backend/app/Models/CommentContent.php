<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CommentContent extends Model
{
    protected $table = "comment_content";
    public $timestamps = false;
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }

    protected $fillable = [
        'comment_id',
        'comment_content_id',
        'comment_content_main',
    ];
}
