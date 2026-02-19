<?php

namespace App\Models;
use CodeIgniter\Model;

class RequestModel extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id','title','description',
        'category','priority','status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

}
