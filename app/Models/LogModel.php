<?php

namespace App\Models;
use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'request_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'request_id',
        'old_status',
        'new_status',
        'changed_by',
        'role',
        'timestamp'
    ];

    protected $useTimestamps = false;
}
