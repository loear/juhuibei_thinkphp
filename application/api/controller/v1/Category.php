<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/22
 * Time: 7:54
 */


namespace app\api\controller\v1;
use app\common\model\Category as CategoryModel;
use app\lib\exception\CategoryException;


class Category
{

    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');

        if ($categories->isEmpty()) {
            throw new CategoryException();
        }
        return $categories;
    }


}