<?php
/**
 * 生成BLL接口文件
 * @author guangzhengren@sina.com
 * @date 2017-09-29 18:31:29
 */
namespace Php\Controller;
use Think\Controller;
class CreateApiController extends Controller
{
    private $params;//参数
    /**
     * 填写页面
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 提交页面
     */
    public function submit()
    {
        $post = I('post.');
        //处理参数
        $this->checkParams($post);
        //写接口
        $this->writeApi();
        //写action
        if($post['isView'] == '1') $this->writeAction();
        $this->success('操作成功');
    }
    /**
     * 写接口
     */
    private function writeApi()
    {
        //创建目录
        $dir = $this->params['bll_path'].'application/controllers/'.$this->params['module_name'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.lcfirst($this->params['api_name']).'.php';
        if(!file_exists($filename))
        {
            //新建文件
            $content = $this->getApiContent();
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("接口文件已存在<br> ==> <br>".$filename);
        }
    }
    private function getApiContent()
    {
        $text = '';
        $text .= "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";
        $text .="/**\n";
        $text .=" *\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "class ".$this->params['api_name']." extends BLL_Controller\n";
        $text .= "{\n";
        $text .="    /**\n";
        $text .="     *\n";
        $text .="     * @param \n";
        $text .="     * @return \n";
        $text .="     */\n";
        $text .= "    public function ".$this->params['function']."_".$this->params['method']."()\n";
        $text .= "    {\n";
        $text .= "        //验证token\n";
        $text .= "        \$token = \$this->".$this->params['method']."('token') ? trim(\$this->".$this->params['method']."('token')) : '';\n";
        $text .= "        \$check = restAuth(\$token,array('class'=>__CLASS__,'method'=>__FUNCTION__.'".$this->params['module_name']."'));\n";
        $text .= "        false == \$check && Util::errorMsg('token check failed');\n";
        $text .= "        \$params = \$_REQUEST;\n";
        //调用action
        $text .= "        //调用action\n";
        if($this->params['isView'] == '1')
        {
            $text .= "        \$this->load->library('action/".$this->params['action_module']."/".$this->params['action_name']."Action','','action');\n";
        }else
        {
            $text .= "        \$this->load->library('action/".$this->params['module_name']."/".$this->params['api_name']."Action','','action');\n";
        }
        $text .= "        \$result = \$this->action->".$this->params['action_function']."(\$params);\n";
        $text .= "        if(is_array(\$result) && \$result['state'] == 1){\n";
        $text .= "            \$result['status'] = 1;\n";
        $text .= "        }else{\n";
        $text .= "            \$result['status'] = 2;\n";
        $text .= "            \$result['errorPath'] = __CLASS__.'->'.__FUNCTION__;\n";
        $text .= "            if(is_array(\$result) && isset(\$result['errorMsg'])) \$result['error'] = \$result['errorMsg'];\n";
        $text .= "        }\n";
        $text .= "        \$this->response(\$result);\n";
        $text .= "    }\n";
        $text .= "}\n";
        return $text;
    }
    /**
     * 写Action
     */
    private function writeAction()
    {
        //创建目录
        $dir = $this->params['bll_path'].'application/libraries/action/'.$this->params['action_module'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.$this->params['action_name'].'Action.php';
        if(!file_exists($filename))
        {
            //新建文件
            $content = $this->getActionContent();
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("Action文件已存在<br> ==> <br>".$filename);
        }
    }
    /**
     * 获取Action内容
     */
    private function getActionContent()
    {
        $text = '';
        $text .= "<?php\n";
        $text .="/**\n";
        $text .=" *\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "class ".$this->params['action_name']."Action\n";
        $text .= "{\n";
        $text .="    private \$CI;//CI句柄\n";
        $text .="\n";
        $text .="    /**\n";
        $text .="     * 构造函数\n";
        $text .="     */\n";
        $text .= "    public function __construct()\n";
        $text .= "    {\n";
        $text .= "        \$this->CI = &get_instance();\n";
        $text .= "    }\n";
        $text .="    /**\n";
        $text .="     *\n";
        $text .="     * @param \n";
        $text .="     * @return \n";
        $text .="     */\n";
        $text .= "    public function ".$this->params['action_function']."()\n";
        $text .= "    {\n";
        $text .= "        \n";
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
        if(!empty($params['api_name'])) $params['api_name'] = ucfirst($params['api_name']);
        if(!empty($params['action_name'])) $params['action_name'] = ucfirst($params['action_name']);
        $this->params = $params;
    }
}
