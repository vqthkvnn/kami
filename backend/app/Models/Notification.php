<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notification";
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_name', 'user_name');
    }

    protected $fillable = [
        'notification_id',
        'notification_content',
        'notification_date',
        'user_name',
        'notification_type',
    ];

}
