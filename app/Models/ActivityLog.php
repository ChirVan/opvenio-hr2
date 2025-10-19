<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id', 'user_name', 'activity', 'details', 'status'
    ];
    public $timestamps = true;
}
