<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 18:43
 */

namespace app\api\controller\v1;
use app\common\model\Template as TemplateModel;
use app\lib\exception\TemplateException;


class Template
{
    public function getAllTemplates()
    {
        $templates = TemplateModel::all([], 'img');

        if ($templates->isEmpty()) {
            throw new TemplateException();
        }
        return $templates;
    }

}