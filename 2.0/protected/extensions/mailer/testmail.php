<?php  
����require("phpmailer/class.phpmailer.php"); //���ص��ļ�������ڸ��ļ�����Ŀ¼  
����$mail = new PHPMailer(); //�����ʼ�������  
����$address = $_POST['address'];  
����$mail->IsSMTP(); // ʹ��SMTP��ʽ����  
����$mail->CharSet='UTF-8';// �����ʼ����ַ�����  
����$mail->Host = "smtp.exmail.qq.com"; // ������ҵ�ʾ�����  
����$mail->SMTPAuth = true; // ����SMTP��֤����  
����$mail->Username = "noreply@starnavig.com"; // �ʾ��û���(����д������email��ַ)  
����$mail->Password = "StarNavig.CoM63!%$"; // �ʾ�����  
����$mail->From = "noreply@starnavig.com"; //�ʼ�������email��ַ  
����$mail->FromName = "noreply@starnavig.com";  
����$mail->AddAddress("cnhewl@qq.com", "he");//�ռ��˵�ַ�������滻���κ���Ҫ�����ʼ���email����,��ʽ��AddAddress("�ռ���email","�ռ�������")  
����//$mail->AddReplyTo("", "");  
����//$mail->AddAttachment("/var/tmp/file.tar.gz"); // ��Ӹ���  
����//$mail->IsHTML(true); // set email format to HTML //�Ƿ�ʹ��HTML��ʽ  
����$mail->Subject = "PHPMailer�����ʼ�"; //�ʼ�����  
����$mail->Body = "Hello,���ǲ����ʼ�"; //�ʼ�����  
����$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //������Ϣ������ʡ��  
����if(!$mail->Send())  
����{  
����echo "�ʼ�����ʧ��. <p>";  
����echo "����ԭ��: " . $mail->ErrorInfo;  
����exit;  
����}  
����echo "�ʼ����ͳɹ�";  
?>