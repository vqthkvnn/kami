<?php


namespace App\Models;


class PostContent extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "post_content";
    public function post(){
        return $this->belongsTo(Post::class);
    }
    public function account(){
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }
    protected $fillable = [
        'post_id',
        'post_content_id',
        'post_content_title',
        'user_name',
        'post_content_main',
    ];
}
