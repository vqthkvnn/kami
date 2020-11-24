<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $table = "post_tag";
    public $timestamps = false;
    protected $hidden = [
        'pivot'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function tag()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    protected $fillable = [
        'post_id',
        'tag_id',
    ];
}
