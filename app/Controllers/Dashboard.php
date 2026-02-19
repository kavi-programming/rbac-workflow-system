<?php
namespace App\Controllers;
use App\Models\RequestModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model   = new RequestModel();
        $role    = session()->get('role');
        $perPage = 5;
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $status  = $this->request->getGet('status');
        $order   = $this->request->getGet('order') ?? 'desc';

        $direction = ($order === 'asc') ? 'ASC' : 'DESC';

        if ($role == 'user') {
            $builder = $model->where('user_id', session()->get('id'));

            if (!empty($status)) {
                $builder->where('status', $status);
            }

            $data['requests'] = $builder->orderBy('updated_at', $direction)->paginate($perPage, 'default', $page);
        }elseif ($role == 'manager') {
            $builder = $model->where('status', 'Submitted');

            if (!empty($status)) {
                $builder->where('status', $status);
            }

            $data['requests'] = $builder->orderBy('updated_at', $direction)->paginate($perPage, 'default', $page);
        }else { // admin
            $builder = $model->select('requests.*, users.role')
                ->join('users', 'users.id = requests.user_id');

            if (!empty($status)) {
                $builder->where('requests.status', $status);
            }

            $data['requests'] = $builder->orderBy('requests.updated_at', $direction)->paginate($perPage, 'default', $page);
        }

        $data['pager'] = $model->pager;
        $data['selected_status'] = $status;
        $data['current_order']   = $order;

        if ($this->request->isAJAX() && $role == 'user') {
            return view('partials/ajax_response', $data);
        }else if ($this->request->isAJAX() && $role == 'manager') {
            return view('partials/manager_ajax_response', $data);
        }else if($this->request->isAJAX() && $role == 'admin'){
            return view('partials/admin_ajax_response', $data);
        }

        return view('dashboards/' . $role, $data);
    }


    public function manager()
    {
        $model = new RequestModel();

        $builder = $model;

        if ($this->request->getGet('status')) {
            $builder = $builder->where('status',$this->request->getGet('status'));
        }

        if ($this->request->getGet('category')) {
            $builder = $builder->where('category',$this->request->getGet('category'));
        }

        if ($this->request->getGet('priority')) {
            $builder = $builder->where('priority',$this->request->getGet('priority'));
        }

        $data['requests'] = $builder->paginate(10);
        $data['pager'] = $model->pager;

        return view('dashboard/manager',$data);
    }

}
