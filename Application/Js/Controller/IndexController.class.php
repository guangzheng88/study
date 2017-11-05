<?php
/**
 * js代码库首页
 */
namespace Js\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('Index/left');
    }
}