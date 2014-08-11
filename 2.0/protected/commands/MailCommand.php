<?php
class MailCommand  extends CConsoleCommand
{
    public function actionSend()
    {
        F::mailTrace('准备发送邮件任务');

        $mailConfig = Yii::app()->params['mail'];
        $mail = Yii::createComponent('application.extensions.mailer.EMailer');
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->IsSMTP(); // 设定使用SMTP服务
        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl"; // SMTP 安全协议
        $mail->Host = $mailConfig['host']; // SMTP 服务器
        $mail->Port = $mailConfig['port']; // SMTP服务器的端口号
        $mail->Username = $mailConfig['username']; // SMTP服务器用户名
        $mail->Password = $mailConfig['password']; // SMTP服务器密码
        $mail->SetFrom($mailConfig['username'], Yii::app()->name); // 设置发件人地址和名称
        $mail->AddReplyTo($mailConfig['username'], Yii::app()->name);

        $header = "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html; charset=utf-8\r\n";
        $header .= "From: " . Yii::app()->name . " <" . $mailConfig['username'] . ">\r\n";

        $records = Wse::getAllRecords();

        $sended_counter = 0;
        foreach($records as $k => $record){
            if($sended_counter >= 10){
                break;
            }

            $id = $record->getAttribute('id');
            if($id){
                $sended_counter++;
                $mail->Subject = $record->getAttribute('subject');
                $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
                $mail->MsgHTML($record->getAttribute('content'));
                $mail->AddAddress($record->getAttribute('address'), "");
                if (!$mail->Send()) {
                    $emailAddress = $record->getAttribute('address');
                    F::mailError("向 $emailAddress 发送邮件失败");

                    Wse::deleteById($id);
                } else {
                    Wse::deleteById($id);
                }
            }
        }
    }
}