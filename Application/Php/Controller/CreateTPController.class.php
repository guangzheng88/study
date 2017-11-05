<?php
/**
 * 生成TP框架的controller
 * 因每次新增controller特繁琐，不如直接生成算了
 */
namespace Php\Controller;
use Think\Controller;
class CreateTPController extends Controller {
    private $module;
    private $controller;
    /**
     * 生成控制器页
     */
    public function index(){
        $this->display();
    }
    /**
     * 生成模板页
     */
    public function createView()
    {
        $this->display();
    }
    /**
     * 接收post参数生成Controller
     */
    public function submit()
    {
        $post = I('post.');
        $this->checkParams();
        $this->writeFile();
        if($post['isView'] == '1')
        {
            //创建view模板
            $this->writeView();
        }
        $this->success('操作成功');
    }
    /**
     * 接收post参数生成模板
     */
    public function submitView()
    {
        $this->checkParams();
        //创建view模板
        $this->writeView();
        $this->success('操作成功');
    }
    /**
     * 写文件
     */
    private function writeFile()
    {
        $filename = dirname(dirname(dirname(__FILE__))).'/'.$this->module.'/'.'Controller/'.$this->controller.'Controller.class.php';
        if(file_exists($filename)){
            $this->error('控制器已存在');
        }
        $content = $this->getContent();
        $myfile = fopen($filename,"a") or die("权限不足");
        fwrite($myfile,$content);
        fclose($myfile);
        chmod($filename,0666);
    }
    /**
     * 拼接文件内容
     */
    private function getContent()
    {
        $text = '';
        $text .="<?php\n";
        $text .="/**\n";
        $text .=" *\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "namespace ".$this->module."\Controller;\n";
        $text .= "use Think\Controller;\n";
        $text .= "class ".$this->controller."Controller extends Controller\n";
        $text .= "{\n";
        $text .= "    public function ".$this->function."()\n";
        $text .= "    {\n";
        $text .= "        \$this->display();\n";
        $text .= "    }\n";
        $text .= "}\n";
        return $text;
    }
    /**
     * 创建模板
     */
    private function writeView()
    {
        $dir = dirname(dirname(dirname(__FILE__))).'/'.$this->module.'/'.'View/'.$this->controller;
        $res = mkdirs($dir);
        if(!$res == true) exit('创建view层目录失败');
        $filename = $dir.'/'.$this->function.'.html';
        $content = $this->getViewContent();
        $myfile = fopen($filename,"a") or die("权限不足");
        fwrite($myfile,$content);
        fclose($myfile);
        chmod($filename,0666);
    }
    /**
     * 获取view层内容
     */
    private function getViewContent()
    {
        $text = '';
        $text .= "<include file=\"./Application/Admin/View/Public/header.html\" title=\"\"/>\n";
        $text .= "</head>\n";
        $text .= "<body>\n";
        $text .= "\n";
        $text .= "<include file=\"./Application/Admin/View/Public/footer.html\"/>\n";
        return $text;
    }
    /**
     * 验参
     */
    private function checkParams()
    {
        $post = I('post.');
        $this->module = ucfirst(trim($post['module']));
        $this->controller = ucfirst(trim($post['controller']));
        $this->function = trim($post['function']);
    }
}