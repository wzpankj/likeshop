<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\shop\controller;


use app\shop\logic\FileLogic;
use app\common\basics\ShopBase;
use app\common\server\JsonServer;

class File extends ShopBase
{
    /**
     * NOTE: 图片列表
     * @author: 张无忌
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $get['shop_id'] = $this->shop_id;
            $lists = FileLogic::getFile($get);
            return JsonServer::success('获取成功', $lists);
        }
        
        $get = $this->request->get();
        $get['type'] = $get['type'] ?? 10;
        $get['shop_id'] = $get['shop_id'] ?? $this->shop_id;

        return view('', [
            'type'     => $get['type'],
            'category' => json_encode(FileLogic::getCate($get))
        ]);
    }

    /**
     * NOTE: 移动文件
     * @author: 张无忌
     */
    public function move()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $res = FileLogic::move($post);
            if ($res) {
                return JsonServer::success('移动成功', FileLogic::getCate($post["type"]));
            }

            $error = FileLogic::getError() ?: '移动失败';
            return JsonServer::error($error);
        }

        $get = $this->request->get();
        $get['type'] = $get['type'] ?? 10; // 10 图片
        $get['shop_id'] = $get['shop_id'] ?? $this->shop_id;
        return view('', [
            'categoryTree' => FileLogic::categoryToSelectThree($get)
        ]);
    }

    /**
     * NOTE: 删除文件
     * @author: 张无忌
     */
    public function del()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $res = FileLogic::del($post);
            $data = [
                'shop_id' => $this->shop_id,
                'type' => $post['type']
            ];
            if ($res) {
                return JsonServer::success('删除成功', FileLogic::getCate($data));
            }

            $error = FileLogic::getError() ?: '删除失败';
            return JsonServer::error($error);
        }

        return JsonServer::error("请求异常");
    }

    /** ======================== 华丽的分割线, 下面是文件分类相关 ========================**/

    /**
     * NOTE: 新增分类
     * @author: 张无忌
     */
    public function addCate()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['shop_id'] = $this->shop_id;
            $res = FileLogic::addCate($post);
            $data = [
                'type' => $post['type'],
                'shop_id' => $this->shop_id
            ];
            if ($res) {
                return JsonServer::success('新增成功', FileLogic::getCate($data));
            }

            $error = FileLogic::getError() ?: '新增失败';
            return JsonServer::error($error);
        }
        $get = $this->request->get();
        $get['type'] = $get['type'] ?? 10; // 10 图片
        $get['shop_id'] = $get['shop_id'] ?? $this->shop_id;
        return view('addCate', [
            'categoryTree' => FileLogic::categoryToSelect($get)
        ]);
    }

    /**
     * NOTE: 编辑分类
     * @author: 张无忌
     */
    public function editCate()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $res = FileLogic::editCate($post);
            if ($res) {
                $data = [
                    'type' => $post["type"],
                    'shop_id' => $this->shop_id
                ];
                return JsonServer::success('编辑成功', FileLogic::getCate($data));
            }

            $error = FileLogic::getError() ?: '编辑失败';
            return JsonServer::error($error);
        }

        $get = $this->request->get();
        $get['shop_id'] = $this->shop_id;
        return view('editCate', [
            'detail'       => FileLogic::getCateById($get['id']),
            'categoryTree' => FileLogic::categoryToSelect($get)
        ]);
    }

    /**
     * NOTE: 删除分类
     * @author: 张无忌
     */
    public function delCate()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('id');
            $type = $this->request->post('type');
            $res = FileLogic::delCate($id);
            $data = [
                'shop_id' => $this->shop_id,
                'type' => $type
            ];
            if ($res) {
                return JsonServer::success('删除成功', FileLogic::getCate($data));
            }

            $error = FileLogic::getError() ?: '删除失败';
            return JsonServer::error($error, FileLogic::getCate($data));
        }

        return JsonServer::error('请求异常');
    }

    /**
     * 上传视频列表
     */
    public function videoList()
    {
        $get = [
            'type' => 20,
            'shop_id' => $this->shop_id
        ];
        return view('', [
            'type'     => $get['type'], // 视频
            'category' => json_encode(FileLogic::getCate($get))
        ]);
    }
}