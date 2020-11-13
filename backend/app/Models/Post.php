<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "post";
    public $timestamps = false;
    protected $hidden = [
        'pivot'
    ];
    protected $primaryKey = "post_id";

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }

    public function subject_short()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id')
            ->select("subject_id","subject_tag",'subject_color');
    }
    public function comment(){
        return $this->hasMany(Comment::class, 'comment_id','comment_id');
    }
    public function postContent(){
        return $this->hasOne(PostContent::class,'post_id','post_id')
            ->orderByDesc('post_content_id')
            ->select(['post_id','post_content_title', 'post_content_create', 'user_name']);
    }
    public function postVote(){
        return $this->hasMany(PostVote::class,'post_id','post_id');
    }
    public function postTag(){
        return $this->belongsToMany(Tag::class,'post_tag', 'post_id', 'tag_id')
            ->select('tag_content');
    }
    protected $fillable = [
        'post_id',
        'post_content',
        'post_status',
        'subject_id',
        'post_date_create',
        'post_date_delete',
        'user_name',
        'post_note'
    ];

}
