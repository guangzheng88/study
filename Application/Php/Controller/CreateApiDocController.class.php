<?php
/**
 *  生成文档注释
 * @author guangzhengren
 * @date 2017-10-02 17:31:46
 */
namespace Php\Controller;
use Think\Controller;
class CreateApiDocController extends Controller
{
    private $params;
    private $content;
    public function index()
    {
        $this->display();
    }
    /**
     * 生成接口单元测试
     */
    public function submit()
    {
        $this->checkParams();
        $content = $this->getApiTestContent();
        echo '<textarea style="width:100%;height:600px;">';
        echo $this->content;
        echo '</textarea>';
    }
    /**
     * 获取接口单元测试内容
     */
    private function getApiTestContent()
    {
        $text = '';
        $text .= "    /**\n";
        $text .= "     * @author ".$this->params['author']." ".date('Y-m-d H:i:s')."\n";
        $text .= "     * @api {".$this->params['api_method']."} ".$this->params['api_url']." ".$this->params['api_title']."\n";
        $text .= "     * @apiDescription ".$this->params['apiDescription']."\n";
        $text .= "     * @apiGroup ".$this->params['apiGroup']."\n";
        //将斜杠转换成下划线
        $apiName = str_replace('/', '_', $this->params['api_url']).'_'.$this->params['api_method'];
        $text .= "     * @apiName ".$apiName."\n";
        //请求参数
        if(is_array($this->params['key']))
        {
            foreach ($this->params['key'] as $k => $v)
            {
                if ($v == '') continue;
                $paramType = 'paramType'.$k;
                $text .= "     * @apiParam {".$this->params[$paramType]."} ".$v." ".$this->params['value'][$k]."\n";
            }
        }
        //数组请求参数
        if($this->params['arrKey'] != '')
        {
            $dataParams = '';
            $dataParams .= ' {<br>'."\n";
            //遍历数组子参数
            foreach ($this->params['arrParamKey'] as $key=>$val)
            {
                if($val != '')
                {
                    if(($key+1) != count($this->params['arrParamKey']))
                    {
                        $dataParams .= '     * "'.$val.'" : "'.$this->params['arrParamValue'][$key].'",<br>'."\n";
                    }else
                    {
                        $dataParams .= '     * "'.$val.'" : "'.$this->params['arrParamValue'][$key].'"<br>'."\n";
                    }
                }
            }
            $dataParams .= '     * }'."\n";
            $text .= "     * @apiParam {Array} ".$this->params['arrKey']."\n";
            $text .= '     * '.$dataParams;
        }
        //成功返回值
        foreach ($this->params['succeskey'] as $k => $v)
        {
            if ($v == '') continue;
            $paramType = 'successParamType'.$k;
            $text .= "     * @apiSuccess {".$this->params[$paramType]."} ".$v." ".$this->params['succesvalue'][$k]."\n";
        }
        //成功示例
        $text .= "     * @apiSuccessExample {Json} 成功的响应:\n";
        //将换行符拆分成数组
        $array = explode("\n",$this->params['apiSuccessExample']);
        //每一行都补一个*
        foreach($array as $key=>$val)
        {
            $val = trim($val);
            if($key == 0 || ($key+1) == count($array)) {
                $text .= "     * ".$val."\n";
            }else{
                $text .= "     *      ".$val."\n";
            }
        }
        //失败返回参数
        foreach ($this->params['errorkey'] as $key=>$val)
        {
            if ($val == '') continue;
            $paramType = 'errorParamType'.$key;
            $text .= "     * @apiError (Error  200) {".$this->params[$paramType]."} ".$val." ".$this->params['errorvalue'][$key]."\n";
        }
        //失败响应
        $text .= "     * @apiErrorExample {Json} 失败的响应，例如:\n";
        $text .= "     * HTTP/1.1 200 OK\n";
         //将换行符拆分成数组
        $array = explode("\n",$this->params['apiErrorExample']);
        //每一行都补一个*
        foreach($array as $key=>$val)
        {
            $val = trim($val);
            if($key == 0 || ($key+1) == count($array)) {
                $text .= "     * ".$val."\n";
            }else{
                $text .= "     *      ".$val."\n";
            }
        }
        $text .= "     */";
        $this->content = $text;
    }
    /**
     * 验证参数
     */
    private function checkParams()
    {
        $this->params = I('post.');
        foreach ($this->params as $key=>$val)
        {
            if(is_array($val))
            {
                foreach ($val as $k=>$v)
                {
                    $this->params[$key][$k] = trim($v);
                }
            }else
            {
                $this->params[$key] = trim($val);
            }
        }
        if(isset($params['action_name']))  $this->params['action_name'] = ucfirst($this->params['action_name']);
    }
}
