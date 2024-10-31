<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'sender_id', 'receiver_id', 'content'];

    // Quan hệ với bảng User
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Quan hệ với bảng Job
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
