<?php

namespace App\Controllers;

use App\Models\LogModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function history($requestId)
    {
        $logModel = new LogModel();

        $data['logs'] = $logModel->where('request_id',$requestId)->orderBy('created_at','ASC')->findAll();

        return view('admin/history',$data);
    }
}
