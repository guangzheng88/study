<?php
/**
 * 发送短信功能
 * @author 任广正
 * @date 2017-10-26 17:47:48
 */
namespace Php\Controller;
use Think\Controller;
class SmsController extends Controller
{
    private $line = ''; //用来存放短信发送后的返回数据
    /**
     * 页面
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 发送短信
     */
    public function submit()
    {
        $mobile = I('post.mobile');
        $content = I('post.content');
        $code = I('post.code');
        //验证码检测,第二个参数为生成的第几个验证码,我们一般只同时用一个，生成的验证码在session里
        $verify = new \Think\Verify();
        if(!$verify->check($code, ''))
        {
            $this->error('验证码错误');
        }
        $result = $this->sendSms($mobile,$content);
        var_dump($result);
    }
    /*
    * 利用正则取出短信流水号
    */
    private function sendSms($mobile,$content)
    {
        //要post的数据
        $argv = array(
            'sn'=>'SDK-BBX-010-12231', ////替换成您自己的序列号
            'pwd'=>strtoupper(md5('SDK-BBX-010-12231'.'914733')), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
            'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
            'content'=>urlencode($content),//短信内容
            'ext'=>'', //子号
            'rrid'=>'',//默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
            'stime'=>''//定时时间 格式为2011-6-29 11:09:21
        );
        //构造要post的字符串
        foreach ($argv as $key=>$value)
        {
            if ($flag!=0)
            {
                $params .= "&";
            }
            $params.= $key."="; $params.= urlencode($value);
            $flag = 1;
        }
        $length = strlen($params);
        //创建socket连接
        $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno);
        //构造post请求的头
        $header = "POST /webservice.asmx/mdSmsSend_u HTTP/1.1\r\n"; //请求Web服务器的目录地址
        $header .= "Host:sdk2.entinfo.cn\r\n";                      //请求web服务器的ip地址
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; //单数据向服务器提交时所采用的编码类型
        $header .= "Content-Length: ".$length."\r\n"; //表示web服务器返回消息正文的长度
        $header .= "Connection: Close\r\n\r\n";  //表示是否需要持久连接，“Keep-Alive”表示是；
        //添加post的字符串
        $header .= $params."\r\n";
        //发送post的数据
        fputs($fp,$header);
        //$inheader = 1;
        while (!feof($fp))
        {
            $this->line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
        }
        echo $this->line;
        preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/',$this->line,$str);
        $result=explode("-",$str[1]);
        return count($result) > 1 ? false : true;
        /*
        if(count($result)>1)
        echo '发送失败返回值为:'.$this->line."请查看webservice返回值";
        else
        echo '发送成功 返回值为:'.$this->line;
        */
    }
}
