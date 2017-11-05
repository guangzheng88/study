<?php
/**
 * 生成接口单元测试
 * @author guangzhengren@sina.com
 * @date 2017-09-30 14:11:54
 */
namespace Php\Controller;
use Think\Controller;
class CreatePhpUnitController extends Controller
{
    public function index()
    {
        $this->display();
    }
    /**
     * 生成接口单元测试
     */
    public function submitApi()
    {
        $this->checkParams();
        $content = $this->getApiTestContent();
        //创建目录
        $dir = $this->params['bll_path'].'tests/controllers/'.$this->params['module_name'].'/'.lcfirst($this->params['api_name']);
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.lcfirst($this->params['api_name']).ucfirst($this->params['method']).'Test.php';
        if(!file_exists($filename))
        {
            //新建文件
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("文件已存在 :<br>
                ------------------------------------------------------------------------------------------------------------ <br>
                ".$filename.'
                <br> ------------------------------------------------------------------------------------------------------------ ');
        }
        echo '<script>';
        echo 'prompt("测试文件创建成功,请将以下内容配置到xml中 :","<file>../../../tests/controllers/'.$this->params['module_name'].'/'.lcfirst($this->params['api_name']).'/'.lcfirst($this->params['api_name']).ucfirst($this->params['method']).'Test.php</file>")';
        echo '</script>';
        $this->success('操作成功');
    }
    /**
     * 获取接口单元测试内容
     */
    private function getApiTestContent()
    {
        $text = '';
        $text .= "<?php\n";
        $text .="/**\n";
        $text .=" * <简单描述>\n";
        $text .=" * <详细描述>\n";
        $text .=" * 被测接口地址 : http://bll.api.loc/index.php/".$this->params['module_name']."/".lcfirst($this->params['api_name'])."/".$this->params['function']."\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "class ".$this->params['api_name'].ucfirst($this->params['method'])."Test  extends CI_TestCase\n";
        $text .= "{\n";
        $text .="    /**\n";
        $text .="     * 被测文件路径 : application/controllers/".$this->params['module_name']."/".lcfirst($this->params['api_name']).".php\n";
        $text .="     * 被测方法名称 : ".$this->params['function']."_".$this->params['method']."\n";
        $text .="     * 被测方法描述 : \n";
        $text .="     * 被测方法成功返回值 : \n";
        $text .="     * 被测方法失败返回值 : \n";
        $text .="     * @dataProvider give".ucfirst($this->params['function'])."Data\n";
        $text .="     */\n";
        $text .= "    public function test".ucfirst($this->params['function'])."(\$params,\$state)\n";
        $text .= "    {\n";
        $text .= "        //使用rest_client调用接口\n";
        $text .= "        \$res = \$this->CI->rest_interface->".$this->params['method']."('".$this->params['module_name']."/".lcfirst($this->params['api_name'])."/".$this->params['function']."',\$params);\n";
        $text .= "        //错误提示信息\n";
        $text .= "        \$error = isset(\$res->error) ? \$res->error : '';\n";
        $text .= "        //断言变量的类型为object\n";
        $text .= "        \$this->assertInternalType('object',\$res,'返回类型错误');\n";
        $text .= "        //断言不同的参数,返回状态是否正确\n";
        $text .= "        \$this->assertEquals(\$state,\$res->state,\$error);\n";
        $text .= "    }\n";
        $text .="    /**\n";
        $text .="     * 数据供给器\n";
        if(!empty($this->params['key']))
        {
            foreach ($this->params['key'] as $val)
            {
                if(empty($val)) continue;
                $text .="     * @param ".$val."\n";
            }
        }
        $text .="     */\n";
        $text .= "    public function give".ucfirst($this->params['function'])."Data()\n";
        $text .= "    {\n";
        $text .= "        return array(\n";
        $text .= "            //场景1 : 正常情况\n";
        $text .= "            array(\n";
        $text .= "                'params' => array(\n";
        if(!empty($this->params['key']))
        {
            foreach ($this->params['key'] as $key=>$val)
            {
                if(empty($val)) continue;
                $text .= "                    '".$val."' => '".$this->params['value'][$key]."',\n";
            }
        }else{
            $text .= "                    \n";
        }
        $text .= "                ),\n";
        $text .= "                'state' => 1\n";
        $text .= "            ),\n";
        $text .= "            //场景2 : 参数错误\n";
        $text .= "            array(\n";
        $text .= "                'params' => array(\n";
        $text .= "                    'token' => '123456',\n";
        $text .= "                ),\n";
        $text .= "                'state' => 2\n";
        $text .= "            ),\n";
        $text .= "        );\n";
        $text .= "    }\n";
        $text .= "/* -------------------------- 接口成功返回值示例 start --------------------------\n";
        $text .= "\n";
        $text .= "     -------------------------- 接口成功返回值示例 end -------------------------- */\n";
        $text .= "\n";
        $text .= "//* -------------------------- 测试Action部分 start --------------------------\n";
        $text .= "\n";
        $text .= "//     -------------------------- 测试Action部分 end -------------------------- */\n";
        $text .= "}\n";
        // echo '<textarea style="width:500px;height:600px;float:left;">';
        // echo $text;
        // echo '</textarea>';exit;
        return $text;
    }
    /**
     * 验证参数
     */
    private function checkParams()
    {
        $params = I('post.');
        foreach ($params as $key=>$val)
        {
            if(is_array($val))
            {
                foreach ($val as $k=>$v)
                {
                    $params[$key][$k] = trim($v);
                }
            }else
            {
                $params[$key] = trim($val);
            }
        }
        if(isset($params['api_name']))  $params['api_name'] = ucfirst($params['api_name']);
        $this->params = $params;
    }
    /**
     * 生成Action单元测试
     */
    public function submitActionUnit()
    {
        $this->checkParams();
        $content = $this->getActionTestContent();
        //创建目录
        $dir = $this->params['bll_path'].'tests/libraries/action/'.$this->params['module_name'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.$this->params['api_name'].'ActionTest.php';
        if(!file_exists($filename))
        {
            //新建文件
            $myfile = fopen($filename,"w") or die("权限不足");
            fwrite($myfile,$content);
            fclose($myfile);
            chmod($filename,0666);
        }else
        {
            exit("文件已存在 :<br>
                ------------------------------------------------------------------------------------------------------------ <br>
                ".$filename.'
                <br> ------------------------------------------------------------------------------------------------------------ ');
        }
        echo '<script>';
        echo 'prompt("测试文件创建成功,请将以下内容配置到xml中 :","<file>../../../tests/libraries/action/'.$this->params['module_name'].'/'.$this->params['api_name'].'ActionTest.php</file>")';
        echo '</script>';
        $this->success('操作成功');
    }
    /**
     * 获取action单元测试内容
     */
    private function getActionTestContent()
    {
        $text = '';
        $text .= "<?php\n";
        $text .="/**\n";
        $text .=" * @description\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "class ".$this->params['api_name']."ActionTest extends CI_TestCase\n";
        $text .= "{\n";
        $text .="    /**\n";
        $text .="     * 被测文件路径 : application/libraries/action/".$this->params['module_name']."/".$this->params['api_name']."Action.php\n";
        $text .="     * 被测方法名称 : ".$this->params['function']."\n";
        $text .="     * 被测方法描述 : \n";
        $text .="     * 被测方法成功返回值 : \n";
        $text .="     * 被测方法失败返回值 : \n";
        $text .="     * @dataProvider give".ucfirst($this->params['function'])."Data\n";
        $text .="     */\n";
        $text .= "    public function test".ucfirst($this->params['function'])."(\$params,\$state)\n";
        $text .= "    {\n";
        $text .= "        //加载被测action类\n";
        $text .= "        \$this->CI->load->library('action/".$this->params['module_name']."/".$this->params['api_name']."Action','','action');\n";
        $text .= "        \$res = \$this->CI->action->".$this->params['function']."(\$params);\n";
        $text .= "        unset(\$this->CI->action);\n";
        $text .= "        //错误提示信息\n";
        $text .= "        \$error = isset(\$res['error']) ? \$res['error'] : '';\n";
        $text .= "        //断言变量的类型为array\n";
        $text .= "        \$this->assertInternalType('array',\$res,'返回类型错误');\n";
        $text .= "        //断言不同的参数,返回状态是否正确\n";
        $text .= "        \$this->assertEquals(\$state,\$res['state'],\$error);\n";
        $text .= "    }\n";
        $text .="    /**\n";
        $text .="     * 数据供给器\n";
        if(!empty($this->params['key']))
        {
            foreach ($this->params['key'] as $val)
            {
                if(empty($val)) continue;
                $text .="     * @param ".$val."\n";
            }
        }
        $text .="     */\n";
        $text .= "    public function give".ucfirst($this->params['function'])."Data()\n";
        $text .= "    {\n";
        $text .= "        return array(\n";
        $text .= "            //场景1 : 正常情况\n";
        $text .= "            array(\n";
        $text .= "                'params' => array(\n";
        if(!empty($this->params['key']))
        {
            foreach ($this->params['key'] as $key=>$val)
            {
                if(empty($val)) continue;
                $text .= "                    '".$val."' => '".$this->params['value'][$key]."',\n";
            }
        }else{
            $text .= "                    \n";
        }
        $text .= "                ),\n";
        $text .= "                'state' => 1\n";
        $text .= "            ),\n";
        $text .= "            //场景2 : 参数错误\n";
        $text .= "            array(\n";
        $text .= "                'params' => array(\n";
        $text .= "                    'token' => '123456',\n";
        $text .= "                ),\n";
        $text .= "                'state' => 1\n";
        $text .= "            ),\n";
        $text .= "        );\n";
        $text .= "    }\n";
        $text .= "/* -------------------------- 成功返回值示例 start --------------------------\n";
        $text .= "\n";
        $text .= "     -------------------------- 成功返回值示例 end -------------------------- */\n";
        $text .= "\n";
        $text .= "}\n";
        return $text;
    }
}
