<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:20
 */

namespace app\admin\controller;
use app\common\model\Banner as BannerModel;
use think\Request;


class Banner extends BaseController
{
    public function index()
    {
        $model = BannerModel::getBannerAll();
        $banner = $model->toArray();
        $this->assign(compact('banner'));
        return view();
    }

    public function add()
    {
        $position = BannerModel::all();
        $this->assign(compact('position'));
        return view();
    }

    public function addProcess(Request $request)
    {
        if ($request->isPost()) {
            $banner_id = $request->post('banner_id');
            $model = BannerModel::find($banner_id);
            $type      = $request->post('type');
            $keywords  = $request->post('keywords');
            $model->items()->save([
                'banner_id' =>  $banner_id,
                'type'      =>  $type,
                'keywords'  =>  $keywords
            ]);
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function position()
    {
        return view();
    }

    public function positionProcess(Request $request)
    {
        if ($request->isPost()) {
            $model = new BannerModel();
            $model->name        = $request->post('name');
            $model->description = $request->post('description');
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function edit($id)
    {
        $position = BannerModel::all();
        $data = BannerModel::getItemById($id);
        return view('edit', compact('data', 'position'));
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $type      = $request->post('type');
            $keywords  = $request->post('keywords');
            $result = BannerModel::saveItem($request->post('id'), $type, $keywords);
            if ($result) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
            return json(['status' => 'failed', 'code'  =>  $keywords]);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function delete(Request $request)
    {
        if ($request->post('id')) {
            BannerModel::destroyItem($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function changeImage(Request $request)
    {
        $tid = $request->post('tid');
        $id = $request->post('id');
        if ($tid && $id) {
            $result = BannerModel::saveImageId($tid, $id);
            if ($result) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
        }
        return json(['status' => 'failed', 'code'  =>  '10000']);
    }



}