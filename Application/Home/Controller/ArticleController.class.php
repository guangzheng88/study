<?php
/**
 * 文章搜索结果
 * @author 任广正
 * @date 2017-11-02 17:26:36
 */
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController
{
    /**
     * 文章详情页
     */
    public function index()
    {
        $id = I('get.id',0,'intval');
        $data = M('article')->where(array('id'=>$id))->find();
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 搜索结果列表页
     */
    public function getList()
    {
        $serch = trimParams(I('get.'));
        $wd = I('get.wd');
        if(!empty($serch['pid']))
        {
            //根据分类id查询文章
            $map['cate_id'] = array('eq',intval($serch['pid']));
            //查询分类名称
            $cateMap['id'] = array('eq',intval($serch['pid']));
            $wd = M('cate')->where($cateMap)->getField('title');
        }
        if($serch['wd'] != '')
        {
            //查询关键字
            $map['keywords'] = array('like','%'.$serch['wd'].'%');
        }
        //查询总数
        $count = M('article')->where($map)->count();
        $data = M('article')->where($map)->order('id desc')->select();
        $this->assign('data',$data);
        $this->assign('wd',$wd);
        $this->assign('count',$count);
        $this->display();
    }
}
