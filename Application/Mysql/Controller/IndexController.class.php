<?php
/**
 * mysql
 * @date 2017-10-12
 */
namespace Mysql\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('Index/left');
    }
}