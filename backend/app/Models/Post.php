<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "post";
    public function account(){
        return $this->belongsTo('App\Models\Account', 'user_name', 'user_name');
    }
    public function subject(){
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'subject_id');
    }
}
