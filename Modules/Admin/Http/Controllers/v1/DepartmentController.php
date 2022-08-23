<?php

/**
 *部门
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Models\Department;
use Modules\Admin\Services\BaseApiService;
use Modules\Blog\Http\Requests\CommonIdRequest;

class DepartmentController extends BaseApiController
{

    public function __construct()
    {
        parent::__construct();

    }


    public function index(Request $request, BaseApiService $service)
    {


        $post = $request->all();


        $list = Department::where(function ($q) use ($post) {
//            if (!empty($post['id'])) {
//                $q->where('id', $post['id']);
//            }

        })->orderBy('id')
            ->get()
            ->keyBy('id')
            ->toArray();

        $items = $list;
        $tree = [];
        foreach ($items as $key => $value) {
            if (isset($items[$value['parant_id']])) {
                $items[$value['parant_id']]['children'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }


        return $service->apiSuccess('', $tree);

    }


    public function departUser(Request $request, BaseApiService $service)
    {

        $post = $request->all();
        $list = Department::where(function ($q) use ($post) {
//            if (!empty($post['id'])) {
//                $q->where('id', $post['id']);
//            }

        })->with([
            'userList' => function ($query) {
                $query->where('status', 1);
                $query->selectRaw('id,name,phone,username,group_id,department_id,post_name');
            },

        ])
            ->orderBy('id', 'desc')
            ->get()
            ->keyBy('id')
            ->toArray();

        $items = $list;

        $tree = [];
        foreach ($items as $key => $value) {
            if (isset($items[$value['parant_id']])) {
                $items[$value['parant_id']]['children'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }

        return $service->apiSuccess('', $tree);
    }


    public function store(Request $request, BaseApiService $service)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'title.required' => '需要分类名字',
        ];

        $request->validate($rules, $messages);
        $data = $request->all();


        $this->path[] = $data['parant_id'];
        $this->getPath($data['parant_id']);
        $data['path'] =  implode(',',array_reverse( $this->path));

        $service->commonCreate(Department::query(), $data);


        return $service->apiSuccess();

    }

    private $flag = true;
    private $path = [];

    private function getPath($id)
    {
        while ($this->flag) {
            $res = Department::where('id', $id)->first();
            if ($res['parant_id'] == 0) {
                $this->flag = false;
                break;
            } else {
                $this->path[] = $res['parant_id'];
                $this->getPath($res['parant_id']);
            }

        }


    }

    public function edit(Request $request, BaseApiService $service)
    {
        return (new BaseApiService())->edit($request->get('id'));
    }

    public function update(Request $request, BaseApiService $service)
    {
        Department::where('id', $request->all('id'))->update($request->only([
            'name',
        ]));

        return $service->apiSuccess();
    }


    public function cDestroy(CommonIdRequest $request, BaseApiService $service)
    {
        return $service->commonIsDelete(Department::query(), $request->only(['id']));

    }


}
