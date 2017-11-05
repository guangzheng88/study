<?php
/**
 * 新增DAL控制器
 * @author 任广正
 * @date 2017-11-01 09:37:36
 */
namespace Php\Controller;
use Think\Controller;
class CreateDalApiController extends Controller
{
    private $params;//参数
    /**
     * 页面
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 新增controller
     */
    public function submit()
    {
        $post = I('post.');
        //处理参数
        $this->checkParams($post);
        //写
        $this->writeApi();
        unset($post['bll_path']);
        $this->success('操作成功',U('createDalApi/createModel',$post));
    }
    /**
     * 写
     */
    private function writeApi()
    {
        //创建目录
        $dir = $this->params['bll_path'].'application/controllers/'.$this->params['module_name'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.lcfirst($this->params['api_name']).'.php';
        // var_dump($filename);exit;
        if(!file_exists($filename))
        {
            //新建文件
            $content = $this->getApiContent();
            // echo '<textarea style="width:600px;height:600px;">';
            // echo $content;
            // echo '</textarea>';
            // exit();
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("文件已存在<br> ==> <br>".$filename);
        }
    }
    private function getApiContent()
    {
        $dbName = preg_replace('/_([A-Za-z])/e',"strtoupper('$1')",$this->params['db_name']);//把下划线命名转换成驼峰
        $text = '';
        $text .= "<?php\n";
        $text .= "if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";
        $text .="/**\n";
        $text .=" * ".$this->params['tableComment']."\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "require_once(APPPATH.'libraries/My_Controller_V1.php');\n";
        $text .= "class ".ucfirst($this->params['api_name'])." extends My_Controller_V1\n";
        $text .= "{\n";
        $text .="    /**\n";
        $text .="     * 构造器\n";
        $text .="     */\n";
        $text .= "    public function __construct()\n";
        $text .= "    {\n";
        $text .= "         \$this->_dbModel = '".$dbName."/".lcfirst($this->params['api_name'])."_dao_mdl';\n";
        $text .= "         parent::__construct();\n";
        $text .= "    }\n";
        $text .= "}\n";
        return $text;
    }
    /**
     * 处理参数
     */
    private function checkParams($params)
    {
        foreach ($params as $key=>$val)
        {
            if(empty($val)) unset($params[$key]);
            $params[$key] = trim($val);
        }
        $this->params = $params;
    }
    /**
     * 新增DAL Model页面
     */
    public function createModel()
    {
        $this->display();
    }
    /**
     * 新增 model
     */
    public function submitModel()
    {
        $post = I('post.');
        //处理参数
        $this->checkParams($post);
        //写
        $this->writeModel();
        $this->success('操作成功');
    }
    /**
     * 写
     */
    private function writeModel()
    {
        //创建目录
        $dbName = preg_replace('/_([A-Za-z])/e',"strtoupper('$1')",$this->params['db_name']);//把下划线命名转换成驼峰
        $dir = $this->params['bll_path'].'application/models/'.$dbName;
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.lcfirst($this->params['api_name']).'_dao_mdl.php';
        // var_dump($filename);exit;
        if(!file_exists($filename))
        {
            //新建文件
            $content = $this->getModelContent();
            // echo '<textarea style="width:600px;height:600px;">';
            // echo $content;
            // echo '</textarea>';
            // exit();
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("文件已存在<br> ==> <br>".$filename);
        }
    }
    private function getModelContent()
    {
        $text = '';
        $text .= "<?php\n";
        $text .= "if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";
        $text .="/**\n";
        $text .=" * ".$this->params['tableComment']."\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "require_once(APPPATH.'libraries/Mysql_Model_V1.php');\n";
        $text .= "class ".ucfirst($this->params['api_name'])."_dao_mdl extends Mysql_Model_V1\n";
        $text .= "{\n";
        $text .="    /**\n";
        $text .="     * 构造器\n";
        $text .="     */\n";
        $text .= "    public function __construct()\n";
        $text .= "    {\n";
        $text .= "         parent::__construct();\n";
        $text .= "         \$this->_table='".lcfirst($this->params['api_name'])."';\n";
        $text .= "         \$this->_pkey = '".$this->params['pri_name']."';\n";
        $text .= "         \$this->_charset = '".$this->params['char_name']."';\n";
        $text .= "    }\n";
        $text .= "}\n";
        return $text;
    }
}
