<?php
/**
 * 验证码
 * @author 任广正
 * @date 2017-10-26 16:59:18
 */
namespace Php\Controller;
use Think\Controller;
class CaptchaController extends Controller
{
    /**
     * 前台页面
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 提交页面验证验证码
     */
    public function submit()
    {
        $code = I('code');
        //验证码检测,第二个参数为生成的第几个验证码,我们一般只同时用一个，生成的验证码在session里
        $verify = new \Think\Verify();
        if($verify->check($code, ''))
        {
            $this->success('验证码通过验证');
        }else
        {
            $this->error('验证码错误');
        }
    }
    /**
     * TP框架自带验证码
     */
    public function captcha()
    {
        $config =    array(
            'fontSize' => 35,    // 验证码字体大小
            'length' => 4,     // 验证码位数
            'useNoise' => true, // 是否添加杂点 默认为true
            'expire' => 900,//验证码的有效期（秒）
            'useCurve' => true,//是否使用混淆曲线 默认为true
            'imageW' => 0,//验证码宽度 设置为0为自动计算
            'imageH' => 0,//验证码高度 设置为0为自动计算
            'fontttf' => '5.ttf',//验证码字体,1-6
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
}
