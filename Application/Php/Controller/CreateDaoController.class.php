<?php
/**
 * 新增BLL Dao
 * @author 任广正
 * @date 2017-10-31 15:34:45
 */
namespace Php\Controller;
use Think\Controller;
class CreateDaoController extends Controller
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
     * 新增dao
     */
    public function submit()
    {
        $post = I('post.');
        //处理参数
        $this->checkParams($post);
        //写dao
        $this->writeApi();
        unset($post['bll_path']);
        $this->success('操作成功',U('createModel/index',$post));
    }
    /**
     * 写
     */
    private function writeApi()
    {
        //创建目录
        $dir = $this->params['bll_path'].'application/libraries/dao/'.$this->params['module_name'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.$this->params['api_name'].'Dao.php';
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
            exit("接口文件已存在<br> ==> <br>".$filename);
        }
    }
    private function getApiContent()
    {
        $text = '';
        $text .= "<?php\n";
        $text .="/**\n";
        $text .=" *\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "require_once(APPPATH.'libraries/dao/Dao.php');\n";
        $text .= "class ".$this->params['api_name']."Dao extends Dao\n";
        $text .= "{\n";
        $text .= "    public function __construct(\$filePath)\n";
        $text .= "    {\n";
        $text .= "        parent::__construct(\$filePath[0],\$filePath['sign']);\n";
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
        $this->params = $params;
    }
}
