<?php
/**
 * 生成二维码控制器
 * @author guangzhengRen
 * @date 2017-10-13 11:09:21
 */
namespace Php\Controller;
use Think\Controller;
class QrcodeController extends Controller
{
    /**
     * 填写页面
     */
    public function index()
    {
        $this->display();
    }
    /**
     * 生成二维码
     */
    public function createQrcode()
    {
        $content = I('post.content');
        $isSave = I('post.isSave');
        $savaPath = I('post.savaPath');
        //导入二维码核心程序
        //这里遇到了一个调用问题，参考:http://www.thinkphp.cn/topic/28527.html
        //导入类之后，应这样生成，$QRcode = new \QRcode();
        //（注意，如果你的类库没有使用命名空间定义的话，实例化的时候需要加上根命名空间，官方文档原话）
        Vendor('PHPQRcode.phpqrcode');
        $QRcode = new \QRcode ();
        $level = 'L';//控制二维码容错率(默认'L'),不同的参数表示二维码可被覆盖的区域百分比L(QR_ECLEVEL_L,7%),M(QR_ECLEVEL_M,15%),Q(QR_ECLEVEL_Q,25%),H(QR_ECLEVEL_H,30%)
        $size = 10;//控制生成图片的大小，默认为4
        $margin = 2;//控制生成二维码的空白区域大小
        if($isSave == '1'){
            //生成文件名 文件路径+图片名字前缀+md5(名称)+.png
            $filename = date('YmdHis').'.png';
            $filePath = $savaPath.'/'.$filename;
            $saveandprint = true;//保存二维码图片并显示出来
            $QRcode::png($content,$filePath,$level,$size,$margin,$saveandprint);
            echo '<img src="/Public/uploads/qrcode/'.$filename.'"><br>';
            echo '图片保存成功<br>';
            echo '图片路径 : '.$filePath;
        }else{
            $filename = '';
            $saveandprint = false;//保存二维码图片并显示出来
            //开始生成
            header("Content-type: image/png");
            $QRcode::png($content,$filename,$level,$size,$margin,$saveandprint);
        }
    }
}
