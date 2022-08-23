<?php

namespace Modules\Admin\Models;
use Modules\Common\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class BaseApiModel extends BaseModel
{

    /**
     * @desc       重写查询条件where
     * @author     文明<736038880@qq.com>
     * @date       2022-07-08 18:15
     * @param array $where
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function baseQuery(array $where)
    {
        $query = self::query();
        if (!empty($where['where'])) {
            foreach ($where['where'] as $val) {
                $query = $query->where(...array_values($val));
            }
        }

        //in 查询
        if (!empty($where['whereIn'])) {
            foreach ($where['whereIn'] as $val) {
                $query = $query->whereIn(...array_values($val));
            }
        }

        if (!empty($where['whereOr'])) {
            foreach ($where['whereOr'] as $val) {
                $query = $query->orWhere(...array_values($val));
            }
        }

        //between查询
        if (!empty($where['between'])) {
            foreach ($where['between'] as $val) {
                $query = $query->whereBetween(...array_values($val));
            }
        }

        if(!empty($where['order'])){
            foreach ($where['order'] as $column => $val) {
                $query = $query->orderBy($column, $val);
            }
        }

        if(!empty($where['group'])){
            $query = $query->groupBy($where['group']);
        }

        if(!empty($where['with'])){
            $with = [];
            foreach ($where['with'] as $name => $column){
                $with[$name] = (function($query)use($column){
                    $query->select(DB::raw($column));
                });
            }
            $query = $query->with($with);
        }

        return $query;
    }

    /**
     * @desc       公共获取列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:56
     * @param array $where
     * @param int   $limit
     *
     * @return array
     */
    public static function getPageList(array $where, $limit = 10){
        $list = self::baseQuery($where)
            ->paginate($limit)
            ->toArray();
        return ['list' => $list['data'], 'total' => $list['total']];
    }
}
