<?php 
class SmsUtil{
	
	/**
	 * 短信发送接口
	 * @param string $phoneListStr eg:18606716336,13221026754
	 * @param string $msg
	 * @author zhangzhicheng
	 */
	public static function send($phoneListStr,$msg){
		if(empty($msg) || empty($phoneListStr)){
			exit("phoneListStr or msg can not be empty!");
		}
		$phone_number_list = explode(',',$phoneListStr);
		$phone_number_cnt = count($phone_number_list);
		$msg = mb_strlen($msg)>350?mb_substr($msg,0,350):$msg;
		//view-source:http://ws.montnets.com:9002//MWGate/wmgw.asmx/MongateCsSpSendSmsNew?userId=JC2506&password=220336&pszMobis=13456720404&pszMsg=abc&iMobiCount=1&pszSubPort=*
		$params = array(
				'userId'=>'J20030',
				'password'=>'330210',
				'pszMobis'=>implode(",",$phone_number_list),
				'pszMsg'=>$msg,
				'iMobiCount'=>$phone_number_cnt,
				'pszSubPort'=>'*',
		
		);
		$send_uri = "http://ws.montnets.com:7902//MWGate/wmgw.asmx/MongateCsSpSendSmsNew?".http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $send_uri);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);
		$p = xml_parser_create();
		xml_parse_into_struct($p, $data, $vals, $index);
		xml_parser_free($p);
		//大于10位小于25
		if(isset($vals[0]['value']) && strlen($vals[0]['value'])>10 && strlen($vals[0]['value'])<25){
			return true;
		}
		return false;
	}	
}