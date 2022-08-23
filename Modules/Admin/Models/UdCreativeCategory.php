<?php

namespace Modules\Admin\Models;
use Illuminate\Support\Facades\DB;

class UdCreativeCategory extends BaseApiModel
{
	/**
	 **/
    protected $table = 'ud_creative_categorys';
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }

    /**
     *获取分类文字
     */
    public static function getCategoryName(int $id)
    {
        $model = new UdCreativeCategory();
        $categoryName = $model->select('a.values as avalues','b.values as bvalues','c.values as cvalues')
                ->from('ud_creative_categorys as a')
                ->where('a.id', $id)
                ->leftJoin('ud_creative_categorys as b', 'a.pid', '=', 'b.id')
                ->leftJoin('ud_creative_categorys as c', 'b.pid', '=', 'c.id')
                ->first()->toArray();
        return isset($categoryName) ? $categoryName['cvalues'].'/'.$categoryName['bvalues'].'/'.$categoryName['avalues'] : '';
    }
}
