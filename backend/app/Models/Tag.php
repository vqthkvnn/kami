<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tag";
    protected $primaryKey = "tag_id";
    public $timestamps = false;
    protected $hidden = [
        'pivot'
    ];

    public function postTag()
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    protected $fillable = [
        'tag_id',
        'tag_content',
        'tag_note'
    ];
}
