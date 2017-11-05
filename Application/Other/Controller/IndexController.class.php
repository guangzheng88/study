<?php
namespace Other\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('left');
    }
}