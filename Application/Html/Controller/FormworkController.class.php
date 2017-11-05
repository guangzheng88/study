<?php
/**
 * 模板控制器
 * @author guangzhengRen
 * @date 2017-10-12 14:29:59
 */
namespace Html\Controller;
use Think\Controller;
class FormworkController extends Controller
{
    /**
     * 添加模板
     */
    public function addForm()
    {
        $this->display();
    }
    /**
     * 处理添加
     */
    public function submitAdd()
    {
        $post = I('post.');
        if(empty($post['title']) || empty($post['content'])) {
            $this->error('参数错误');
        }
        $post['create_time'] = date('Y-m-d H:i:s');
        $post['update_time'] = date('Y-m-d H:i:s');
        $res = M('code')->data($post)->add();
        if($res != false){
            $this->success('操作成功','formList');
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 模板列表
     */
    public function formList()
    {
        $result = M('code')->field('id,title,keywords')->select();
        $this->assign('data',$result);
        $this->display();
    }
    /**
     * 模板详情
     */
    public function index()
    {
        $id = I('get.id',0,'intval');
        $condition['id'] = array('eq',$id);
        $data = M('code')->where($condition)->find();
        $this->assign('data',$data);
        $this->display();
    }
}
