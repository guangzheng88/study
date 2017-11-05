<?php
/**
 * 后台首页
 * @author guangzhengren@sina.com
 * @date 2017-09-28 16:19:38
 */
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    /**
     * 首页
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 头部
     */
    public function top()
    {
        $this->display();
    }
    /**
     * 左侧
     */
    public function left()
    {
        $this->display();
    }
    /**
     * 右侧
     */
    public function main()
    {
        $date = date('H');
        if($date >= 5 && $date <8)
        {
            $assign['hello'] = '早上好!';
        }else if($date >= 8 && $date < 12)
        {
            $assign['hello'] = '上午好!';
        }else if($date >= 12 && $date < 14)
        {
            $assign['hello'] = '中午好!';
        }else if($date >= 14 && $date < 18)
        {
            $assign['hello'] = '下午好!';
        }else if(($date >= 18 && $date < 23) || $data > 23)
        {
            $assign['hello'] = '晚上好!';
        }else{
            $assign['hello'] = '凌晨好!';
        }
        $assign['date'] = date('Y-m-d H:i:s');
        $this->assign('sysInfo', $assign);
        $this->display();
    }
}