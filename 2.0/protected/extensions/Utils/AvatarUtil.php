<?php
class AvatarUtil {
	
	public static function avatarUrl()
	{
		$accountId = intval($_GET['id']);
		if(!empty($accountId)){
			$avatar = AccountService::model()->idFindDetail(10000,'avatar');
			$imgPath = !empty($avatar)?date("Ymd",intval(($avatar))).'/'.$avatar:'';
			header("Location: ".TrHelper::avartar($imgPath,'small',$style=FALSE,'boy','s'));
			exit;
		}
	}

}