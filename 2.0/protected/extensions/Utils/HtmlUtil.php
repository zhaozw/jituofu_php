<?php
class HtmlUtil {
	
	/*
	 * 档案搜索，Tag分类搜索
	 */
	public static function ArchiveTags($list){
		$data=array();
		foreach ($list as $key=>$item){
			$data[]=self::createArchivesHtml($item);			
		}
		return $data;
	}
	
	
	private static function createArchivesHtml($item){
		if(empty($item))
			return '';
		$type = '';
		foreach ($item as $key=>$val){
			$type = substr($key, 0,strpos($key, '_'));
			break;
		}
		$htmlStr = '';
		if($type=='log'){
// 			var_dump($item);exit;
			//日志
			$htmlStr='<li>
					<div class="journal">
						<div class="head">'.date('H:i:s',$item['in_time']).'</div>
						<div class="txt"><a href="'.Yii::app()->createUrl('api/growthView',array('gid'=>$item['log_id'],'sign'=>$item['sign'])).'">'.TrHelper::cut_str(htmlspecialchars_decode($item['content']),62).'</a></div>
					</div>
				 </li>';
		}elseif($type=='photo'){
			//图片
			$htmlStr='<li>
					<div class="photo">
						<div class="img"><a href="'.Yii::app()->createUrl('api/growthView',array('gid'=>$item['photo_id'],'album_id'=>$item['album_id'],'count'=>$item['count'],'name'=>base64_encode($d['name']),'sign'=>$item['sign'])).'"><img src="'.TrHelper::imagesPath($item['path'], $item['name'],'cover').'" width="220" height="130" alt="" /></a></div>
						<div class="time"><i></i>'.date('H:i:s',$item['in_time']).'</div>
					</div>
				 </li>';
		}elseif($type=='video'){
			//视频
			$htmlStr='<li>
					<div class="video">
						<div class="img"><a href="'.Yii::app()->createUrl('api/growthView',array('gid'=>$item['video_id'],'sign'=>$item['sign'],'name'=>base64_encode($item['name']))).'"><img src="'.TrHelper::videoPhoto($item['path'], $item['name']).'" width="220" height="130" alt="" /></a></div>
						<div class="time"><i></i>'.date('H:i:s',$item['in_time']).'</div>
					</div>
				 </li>';
		}elseif($type=='recorder'){
			//音频
			$recorderUrl = TrHelper::recorderCover($item['path'], $item['name']);
			$htmlStr='<li><div class="audio"><div class="audio_play"><audio controls="controls" style="width:250px;"><source src="'.$recorderUrl.'" type="audio/mpeg"></audio></div></div></li>';
		}
		return $htmlStr;		
	}

}
