<?php
/**
 * 文章管理控制器
 * @author 任广正
 * @date 2017-11-01 17:16:59
 */
namespace Admin\Controller;
use Think\Controller;
class AddArticleController extends Controller
{
    /**
     * 添加文章
     */
    public function index()
    {
        //查询分类
        $cond['pid'] = array('eq',0);
        $cond['title'] = array('neq','首页');
        $cateInfo = M('cate')->where($cond)->order('sort asc')->select();
        $this->assign('cateInfo',$cateInfo);
        $this->display();
    }
    /**
     * 添加
     */
    public function submit()
    {
        $post = trimParams(I('post.'));
        $post['create_time'] = date('Y-m-d H:i:s');
        $res = M('article')->data($post)->add();
        if($res){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }
}
