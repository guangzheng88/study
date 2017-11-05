<?php
/**
 * 公共控制器
 * @author 任广正
 * @date 2017-11-02 17:40:56
 */
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //查询分类
        $cond['pid'] = array('eq',0);
        $cond['title'] = array('neq','首页');
        $cateInfo = M('cate')->where($cond)->order('sort asc')->select();
        $this->assign('cateInfo',$cateInfo);
    }
}
