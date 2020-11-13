<?php


namespace App\Models;


class PostTag extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "post_tag";
    protected $hidden = [
        'pivot'
    ];
    public function post(){
        return $this->belongsTo(Post::class,'post_id', 'post_id');
    }
    public function tag(){
        return $this->belongsTo(Post::class,'post_id', 'post_id');
    }
    protected $fillable = [
        'post_id',
        'tag_id',
    ];
}
