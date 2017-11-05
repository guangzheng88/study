<?php
/**
 * 获取本地数据库MySql操作日志
 * @author 任广正
 * @date 2017-10-26 13:36:02
 */
namespace Mysql\Controller;
use Think\Controller;
class SqlLogController extends Controller
{
    public function index()
    {
        //获取日志文件内容
        $content = file_get_contents(MYSQL_LOG);
        //正则匹配关键字标亮显示
        $pattern = '/\d{0,2} Query/';
        $content = preg_replace($pattern, '<hr><span style="color:orange;font-weight: bold;font-size:16px;">Query :</span><br>', $content);
        $pattern = '/SELECT/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">SELECT</span>', $content);
        $pattern = '/UPDATE/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">UPDATE</span>', $content);
        $pattern = '/DELETE/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">DELETE</span>', $content);
        $pattern = '/INSERT/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">INSERT</span>', $content);
        $pattern = '/select/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">select</span>', $content);
        $pattern = '/update/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">update</span>', $content);
        $pattern = '/delete/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">delete</span>', $content);
        $pattern = '/insert/';
        $content = preg_replace($pattern, '<hr><span style="color:red;font-weight: bold;font-size:18px;">insert</span>', $content);
        // preg_match_all($pattern, $content,$matche);
        // var_dump($matche[0]);exit;
        // var_dump($content);
        //匹配换行符
        $patten = array("\r\n", "\n", "\r");
        $content=str_replace($patten, "<br>", $content);
        $this->assign('content',$content);
        $this->display();
    }
    /**
     * 清空sql 日志文件
     */
    public function cleanSql()
    {
        $filename = MYSQL_LOG;
        $myfile = fopen($filename,"w") or die("权限不足");
        fwrite($myfile,'');
        fclose($myfile);
        chmod($filename,0666);
        $this->redirect('sqlLog/index');
    }
}
