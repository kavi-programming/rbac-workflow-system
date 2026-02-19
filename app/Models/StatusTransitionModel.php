<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusTransitionModel extends Model
{
    protected $table = 'status_transitions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'current_status',
        'next_status',
        'allowed_role',
        'is_active'
    ];

    public function isAllowed($current, $next, $role)
    {
        return $this->where([
            'current_status' => $current,
            'next_status' => $next,
            'allowed_role' => $role,
            'is_active' => 1
        ])->first();
    }
}
