<?php
namespace Ubuntu\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('Index/left');
    }
}