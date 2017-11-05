<?php
/**
 * 创建BLL Model
 * @author 任广正
 * @date 2017-10-31 16:12:41
 */
namespace Php\Controller;
use Think\Controller;
class CreateModelController extends Controller
{
    private $params;//参数
    private $tableComment;//表注释
    private $fieldComment;//字段注释
    /**
     * 页面
     */
    public function index()
    {
        //将大写字母转换成下划线
        $api_name = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', I('get.api_name')));
        $this->assign('api_name',$api_name);
        $this->display();
    }
    /**
     * 新增model
     */
    public function submit()
    {
        $post = I('post.');
        //处理参数
        $this->checkParams($post);
        //写
        $this->writeApi();
        unset($post['bll_path']);
        $post['tableComment'] = $this->tableComment;
        $this->success('操作成功',U('createDalApi/index',$post));
    }
    /**
     * 写
     */
    private function writeApi()
    {
        //创建目录
        $dir = $this->params['bll_path'].'application/libraries/model/'.$this->params['module_name'];
        $res = mkdirs($dir);
        if(!$res == true) exit('创建目录失败，请检查是否拥有权限');
        //获取文件名
        $filename = $dir.'/'.ucfirst($this->params['api_name']).'.php';
        // var_dump($filename);exit;
        if(!file_exists($filename))
        {
            //获取表结构注释
            $this->getTableStructure();
            // var_dump($this->fieldComment,$this->tableComment);exit;
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
        $text = '';
        $text .= "<?php\n";
        $text .="/**\n";
        $text .=" * ".$this->params['db_name'].'.'.$this->params['api_name'].' '.$this->tableComment."\n";
        $text .=" * @author ".AUTHOR."\n";
        $text .=" * @date ".date('Y-m-d H:i:s')."\n";
        $text .=" */\n";
        $text .= "include_once( APPPATH.'libraries/model/Model.php');\n";
        $text .= "class ".ucfirst($this->params['api_name'])." extends Model\n";
        $text .= "{\n";
        $text .= "    //初始化表属性\n";
        foreach ($this->fieldComment as $key=>$val)
        {
            $text .= "    protected \$".$key." = '".$val['default'].'\'; //'.$val['comment']."\n";
        }
        $text .="    /**\n";
        $text .="     * 魔术方法为属性赋值\n";
        $text .="     */\n";
        $text .= "    public function __set(\$key,\$val)\n";
        $text .= "    {\n";
        $text .= "         \$this->\$key = \$val;\n";
        $text .= "    }\n";
        $text .="    /**\n";
        $text .="     * 魔术方法获取属性值\n";
        $text .="     */\n";
        $text .= "    public function __get(\$key)\n";
        $text .= "    {\n";
        $text .= "         return \$this->\$key;\n";
        $text .= "    }\n";
        $text .="    /**\n";
        $text .="     * 获取object对象中的属性，组成一个数组返回\n";
        $text .="     */\n";
        $text .= "    public function getVal()\n";
        $text .= "    {\n";
        $text .= "         return get_object_vars(\$this);\n";
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
     * 获取表结构
     */
    private function getTableStructure()
    {
        //切换数据库
        C('DB_NAME',$this->params['db_name']);
        //设置字符集
        C('DB_CHARSET',$this->params['char_name']);
        $mo = M($this->params['api_name']);
        //查看字段注释
        $sql = 'show full columns from '.$this->params['api_name'];
        $res = $mo->query($sql);
        // var_dump($res);exit;
        $columnsArr = array();
        foreach ($res as $key=>$val)
        {
            $columnsArr[$val['field']]['comment'] = $val['comment'];
            if($val['extra'] == 'auto_increment'){
                $columnsArr[$val['field']]['default'] = 0;//默认值
            }else{
                $columnsArr[$val['field']]['default'] = $val['default'];//默认值
            }
        }
        $this->fieldComment = $columnsArr;
        //查看表注释
        $sql = 'select * from TABLES where TABLE_SCHEMA="'.$this->params['db_name'].'" and TABLE_NAME="'.$this->params['api_name'].'"';
        $result = $mo->db(1,"mysql://".DB_USER.":".DB_PWD."@".DB_HOST.":3306/information_schema")->query($sql);
        $this->tableComment = $result[0]['table_comment'];
    }
}
