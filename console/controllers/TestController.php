<?php
namespace console\controllers;
use yii\console\Controller;
use Yii;

class TestController extends Controller
{
    public function actionIndex()
    {
        $str = '传说中的控制台？';
        echo iconv('utf-8','gbk',$str);
    }

    public function actionSend()
    {
        $email = ['1079551334@qq.com','1300334237@qq.com','2019169887@qq.com','1206707214@qq.com','applesoft@qq.com'];
        $img = '<a href="https://baike.baidu.com/item/%E5%B9%B4%E8%BD%BB%E4%BA%BA%E4%B8%8D%E8%A6%81%E8%80%81%E7%86%AC%E5%A4%9C/16592221?fr=aladdin"><img src="http://img4.kuwo.cn/star/albumcover/240/77/37/717767107.jpg"></a>';

        $foot = '<div style="float:right;color:#9acd32;size:1px"><i>-- have a good night!</i></div>';

        $messages = [];
        foreach($email as $e) {
            $messages[] = Yii::$app->mailer->compose()
                ->setTo($e)
                ->setSubject("习大大")
                ->setHtmlBody("<div style='width:300px'>{$img}{$foot}</div>");
        }
        $rs = Yii::$app->mailer->sendMultiple($messages);

        if($rs){
            die(iconv('utf-8','gbk','success'));
        }else{
            die(iconv('utf-8','gbk','fail'));
        }
    }
	
	public function actionSendDailyWorkReport()
    {
        $email = ['1079551334@qq.com'];
		$content = "<font font-size="20px"><b>发工作日报！！！<br />立刻，马上！！！</b></font>";
		
        $img = '<a href="https://baike.baidu.com/item/%E5%B9%B4%E8%BD%BB%E4%BA%BA%E4%B8%8D%E8%A6%81%E8%80%81%E7%86%AC%E5%A4%9C/16592221?fr=aladdin"><img src="E:\图片/TIM图片20170918103504.png"></a>';

        $foot = '<div style="float:right;color:#9acd32;size:1px"><i>-- Don\'t forget send daily work report!</i></div>';

        $messages = [];
        foreach($email as $e) {
            $messages[] = Yii::$app->mailer->compose()
                ->setTo($e)
                ->setSubject("记得下班前不要忘发工作日报哦")
                ->setHtmlBody("<div style='width:300px'>{$content}{$img}{$foot}</div>");
        }
        $rs = Yii::$app->mailer->sendMultiple($messages);

        if($rs){
            die(iconv('utf-8','gbk','success'));
        }else{
            die(iconv('utf-8','gbk','fail'));
        }
    }
}