<?php
/**
 * PHP模块左侧导航
 */
namespace Php\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('Index/left');
    }
}