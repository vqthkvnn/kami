<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = "subject";
    public function post(){
        return $this->hasMany(Post::class, 'subject_id', 'subject_id');
    }
    protected $fillable = [
        'subject_id',
        'subject_content',
        'subject_tag',
        'subject_color',
        'subject_note',
    ];
}
