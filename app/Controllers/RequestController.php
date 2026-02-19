<?php

namespace App\Controllers;

use App\Models\RequestModel;
use App\Models\LogModel;
use App\Models\StatusTransitionModel;
use CodeIgniter\Controller;

class RequestController extends Controller
{
    protected $requestModel;
    protected $logModel;
    protected $statusTransitionModel;

    public function __construct()
    {
        $this->requestModel = new RequestModel();
        $this->logModel = new LogModel();
        $this->statusTransitionModel = new StatusTransitionModel();
    }

    // =========================
    // USER CREATE REQUEST
    // =========================
    public function create()
    {
        $rules = [
            'title' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Title is required.',
                    'min_length' => 'Title must be at least 3 characters.'
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Description is required.'
                ]
            ],
            'category' => [
                'rules' => 'required|in_list[Leave,Purchase,IT Support,Travel]',
                'errors' => [
                    'required' => 'Please select a category.'
                ]
            ],
            'priority' => [
                'rules' => 'required|in_list[Low,Medium,High]',
                'errors' => [
                    'required' => 'Please select a priority.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->requestModel->save([
            'user_id'   => session()->get('id'),
            'title'     => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'category'  => $this->request->getPost('category'),
            'priority'  => $this->request->getPost('priority'),
            'status'    => 'Submitted'
        ]);

        return redirect()->to('/dashboard');
    }
    public function new()
    {
        return view('dashboards/create_request');
    }


    // =========================
    // MANAGER ACTION
    // =========================
    public function managerAction()
    {
        if (session()->get('role') !== 'manager') {
            return redirect()->to('/dashboard');
        }
        if ($this->request->isAJAX()) {
            $data       = $this->request->getJSON(true);
            $requestId  = $data['request_id'];
            $action     = $data['action'];
            $this->changeStatus($requestId, $action);

            $color = match($action) {
                'Approved' => 'success',
                'Rejected' => 'danger',
                'Needs Clarification' => 'warning',
                default => 'primary'
            };

            return $this->response->setJSON([
                'status' => 'success',
                'color' => $color
            ]);
        }
        $id     = $this->request->getPost('request_id');
        $action = $this->request->getPost('action');

        return $this->changeStatus($id,$action);
    }

    // =========================
    // ADMIN ACTION
    // =========================
    public function adminAction()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        if ($this->request->isAJAX()) {
            $data   = $this->request->getJSON(true);
            $id     = $data['request_id'];
            $action = $data['action'];
            $this->changeStatus($id, $action);

            $color = match($action) {
                'Closed'   => 'dark',
                'Reopened' => 'info',
                default    => 'secondary'
            };
            return $this->response->setJSON([
                'status' => 'success',
                'color'  => $color
            ]);
        }
        $id     = $this->request->getPost('request_id');
        $action = $this->request->getPost('action');

        return $this->changeStatus($id,$action);
    }

    // =========================
    // STATUS CHANGE HANDLER
    // =========================
    private function changeStatus($id,$newStatus)
    {
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->back()->with('error','Invalid request ID');
        }

        $req = $this->requestModel->find($id);

        if (!$req) {
            return redirect()->back()->with('error','Request not found');
        }

        $oldStatus = $req['status'];

        $role = session()->get('role');
        if ($role === 'manager' && !in_array($newStatus, ['Approved','Rejected','Needs Clarification'])) {
            return redirect()->back()->with('error','Unauthorized action');
        }
        if ($role === 'admin' && !in_array($newStatus, ['Closed','Reopened'])) {
            return redirect()->back()->with('error','Unauthorized action');
        }

        $allowed = $this->statusTransitionModel->isAllowed($oldStatus, $newStatus, $role);

        if (!$allowed) {
            return redirect()->back()->with('error','Invalid or Unauthorized Status Transition');
        }

        $this->requestModel->update($id,['status'=>$newStatus, 'updated_at' => date('Y-m-d H:i:s')]);

        $this->logModel->save([
            'request_id' => $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => session()->get('id'),
            'role'       => session()->get('role'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success','Status updated');
    }

    // =========================
    // USER DASHBOARD
    // =========================
    public function index()
    {
        $perPage = 5;
        $page = (int) ($this->request->getGet('page') ?? 1);

        $requests = $this->requestModel->where('user_id', session()->get('id'))->orderBy('updated_at', 'DESC')->paginate($perPage, 'default', $page);

        $data['requests']   = $requests;
        $data['pager']      = $this->requestModel->pager;
        return view('user', $data);

    }

    // =========================
    // DATE SORT FROM USER
    // =========================
    public function ajaxSort()
    {
        if (!$this->request->isAJAX()) {
            return;
        }
        $order  = $this->request->getGet('order');
        $status = $this->request->getGet('status');
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $direction = ($order === 'asc') ? 'ASC' : 'DESC';

        $builder = $this->requestModel->where('user_id', session()->get('id'));

        if (!empty($status)) {
            if ($status === 'No Action') {
                $builder->where('status', 'Submitted');
            } else {
                $builder->where('status', $status);
            }
        }
        $perPage = 5;

        $requests = $builder->orderBy('updated_at', $direction)->paginate($perPage, 'default', $page);

        $data['requests'] = $requests;
        $data['pager']    = $this->requestModel->pager;
        return view('partials/ajax_response', $data);
    }

    // =========================
    // USER FILTER
    // =========================
    public function ajaxFilter()
    {
        if (!$this->request->isAJAX()) {
            return;
        }
        $status = $this->request->getGet('status');
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $builder = $this->requestModel->where('user_id', session()->get('id'));

        if (!empty($status)) {
            if ($status === 'No Action') {
                $builder->where('status', 'Submitted');
            } else {
                $builder->where('status', $status);
            }
        }

        $perPage = 5;
        $requests = $builder->orderBy('updated_at', 'DESC')->paginate($perPage, 'default', $page);

        $data['requests'] = $requests;
        $data['pager']    = $this->requestModel->pager;
        return view('partials/ajax_response', $data);
    }

    // =========================
    // MANAGER FILTER
    // =========================
    public function managerFilter()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status'=>'error']);
        }

        $category = $this->request->getGet('category');
        $priority = $this->request->getGet('priority');
        $status   = $this->request->getGet('status');
        $page     = (int) ($this->request->getGet('page') ?? 1);

        $builder = $this->requestModel;

        if (empty($status)) {
            $builder->where('status', 'Submitted');
        } else {
            $builder->where('status', $status);
        }

        if (!empty($category)) {
            $builder->where('category', $category);
        }

        if (!empty($priority)) {
            $builder->where('priority', $priority);
        }
        $perPage = 5;
        $requests = $builder->orderBy('updated_at', 'DESC')->paginate($perPage, 'default', $page);

        $data['requests'] = $requests;
        $data['pager']    = $this->requestModel->pager;

        return view('partials/manager_ajax_response', $data);
    }

    // =========================
    // ADMIN FILTER
    // =========================
    public function adminFilter()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status'=>'error']);
        }

        $role   = $this->request->getGet('role');
        $status = $this->request->getGet('status');
        $start  = $this->request->getGet('start');
        $end    = $this->request->getGet('end');
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $builder = $this->requestModel->select('requests.*, users.role')->join('users', 'users.id = requests.user_id');

        if (!empty($role)) {
            $builder->where('users.role', $role);
        }
        if (!empty($status)) {
            $builder->where('requests.status', $status);
        }

        if (!empty($start)) {
            $builder->where('requests.updated_at >=', $start . ' 00:00:00');
        }
        if (!empty($end)) {
            $builder->where('requests.updated_at <=', $end . ' 23:59:59');
        }
        $perPage    = 5;
        $requests   = $builder->orderBy('requests.updated_at', 'DESC')->paginate($perPage, 'default', $page);

        $data['requests'] = $requests;
        $data['pager']    = $this->requestModel->pager;
        return view('partials/admin_ajax_response', $data);
    }

    // =========================
    // VIEW FULL HISTORY LOGS
    // =========================
    public function viewLogs($requestId)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }
        if (!is_numeric($requestId)) {
            return redirect()->to('/dashboard');
        }
        $logs = $this->logModel->where('request_id', $requestId)->orderBy('created_at','ASC')->findAll();

        return view('admin/request_history', [
            'logs' => $logs
        ]);
    }

    // =========================
    // SOFT DELETE REQUEST
    // =========================
    public function deleteRequest($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        if (!is_numeric($id)) {
            return redirect()->back()->with('error','Invalid ID');
        }

        $this->requestModel->delete($id);
        return redirect()->back()->with('success','Request Deleted');
    }

    // =========================
    // EDIT REQUEST
    // =========================
    public function edit($id)
    {
        $request = $this->requestModel->find($id);

        if (!$request) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($request['user_id'] != session()->get('id')) {
            return redirect()->to('/dashboard')->with('error','Unauthorized access');
        }

        return view('dashboards/edit_request', ['request' => $request]);
    }

    // =========================
    // RESUBMIT THE EDITTED REQUEST
    // =========================
    public function resubmit($id)
    {
        $request = $this->requestModel->find($id);

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }

        // Restrict to owner
        if ($request['user_id'] != session()->get('id')) {
            return redirect()->to('/dashboard')->with('error','Unauthorized access');
        }

        $rules = [
            'title'         => 'required|min_length[3]',
            'description'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $allowed = $this->statusTransitionModel->isAllowed(
            $request['status'],
            'Submitted',
            session()->get('role')
        );
        if (!$allowed) {
            return redirect()->back()->with('error','Transition not allowed');
        }

        $this->requestModel->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status'      => 'Submitted'
        ]);

        $this->logModel->save([
            'request_id' => $id,
            'old_status' => 'Needs Clarification',
            'new_status' => 'Submitted',
            'changed_by' => session()->get('id'),
            'role'       => session()->get('role'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/dashboard')
            ->with('success','Request Resubmitted Successfully');
    }
}
