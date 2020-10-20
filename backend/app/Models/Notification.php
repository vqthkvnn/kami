<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notification";
    public function account(){
        return $this->belongsTo('App\Models\Account', 'user_name', 'user_name');
    }

}
