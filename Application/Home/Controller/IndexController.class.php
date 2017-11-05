<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common;
class IndexController extends CommonController {
    /**
     * 网站首页
     */
    public function index()
    {
        $this->display();
    }
}