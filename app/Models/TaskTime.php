<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTime extends Model
{
    use HasFactory;
    protected $fillable  = [
        'comments_fact',
        'timestamp_fact'
    ];
    protected $table = 'task_time';
    
}
