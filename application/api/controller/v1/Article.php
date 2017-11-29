<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/25
 * Time: 7:55
 */

namespace app\api\controller\v1;
use app\common\model\Article as ArticleModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ArticleMissException;


class Article
{
    public function getArticle($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $article = ArticleModel::getArticleById($id);

        if (!$article) {
            throw new ArticleMissException();
        }

        return $article;
    }

}