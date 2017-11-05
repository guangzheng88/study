<?php
/**
 * 分类管理控制器
 * @author 任广正
 * @date 2017-11-01 17:56:59
 */
namespace Admin\Controller;
use Think\Controller;
class CateController extends Controller
{
    public function index()
    {
        //顶级分类
        $cate = M('cate')->where(array('pid'=>0))->select();
        $this->assign('cate',$cate);
        $this->display();
    }
    /**
     * 添加分类
     */
    public function submit()
    {
        $post = trimParams(I('post.'));
        if($post['ppid'] == '0') {
            unset($post['ppid']);
        }else{
            $post['pid'] = $post['ppid'];
        }
        $post['create_time'] = date('Y-m-d H:i:s');
        $res = M('cate')->data($post)->add();
        if(!$res) $this->error('添加失败');
        $this->success('添加成功',U('Admin/Cate/getList'));
        dump($post);exit;
    }
    /**
     * 分类列表
     */
    public function getList()
    {
        $data = M('cate')->where(array('pid'=>0))->order('sort ASC')->select();//顶级分类
        $childArr = array();//二级分类
        foreach($data as $key=>$val)
        {
            $data[$key]['child'] = M('cate')->where(array('pid'=>$val['id']))->order('sort ASC')->select();
            foreach($data[$key]['child'] as $k=>$v)
            {
                $data[$key]['child'][$k]['son'] = M('cate')->where(array('pid'=>$v['id']))->order('sort ASC')->select();
            }
        }
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * ajax获取子分类
     */
    public function ajaxGetCate()
    {
        $pid = I('get.pid',0,'intval');
        if($pid == 0) exit('0');
        $data = M('cate')->where(array('pid'=>$pid))->select();
        $this->ajaxReturn($data);
    }
}
