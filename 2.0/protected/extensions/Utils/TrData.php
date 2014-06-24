<?php
/* *
 * @name TrData class
 * @author kuyuecs
 * @version 1.0
 */
class TrData{
	/* *
	 * 自定义班级类型
	 * @param string $type
 	 * @return string
	 */
	public static function getCustomClassType($params)
	{
		$prefix = Yii::app()->params['memcachePrefix'];
		$cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
		$gartenId = isset($params['gartenId'])?$params['gartenId']:'';
		$key = $prefix.'CustomId'.$gartenId.'@';
		Yii::app()->cache->delete($key);
		$custom = Yii::app()->cache->get($key);
		if(!$custom){
			$colum = empty($params['gradeId'])?'custom_name':'custom_id';
			$criteria = new CDbCriteria;
			$criteria->select = $colum.',class_type';
			$criteria->condition = !empty($gartenId)?"garten_id = '".$gartenId."' and status = 'y'":'';
			$criteria->order = 'orderid desc';
			$data = ClassesCustom::model()->findAll($criteria);
			if($data){
				foreach($data as $d){
					$customType[$d['class_type']] = $d[$colum];
				}
			}else{
				if(isset($params['gradeId']) && !empty($params['gradeId'])){
					$getCustom = TrHelper::getCustom();
					foreach ($getCustom as $gc){
						if($gc['class_type']==$params['type']){
							$customType[$gc['class_type']] = $gc['custom_id'];
						}
					}
				}else{
					$customType = array('b'=>'大班','m'=>'中班','s'=>'小班');
				}
			}
			$defaultType = $colum != 'custom_id'?array('n'=>'泰一指尚','c'=>'自定义'):array();
			$custom = array_merge($customType,$defaultType);
			Yii::app()->cache->set($key, $custom, $cacheExpire);
		}
		return !empty($params['type']) ? @$custom[$params['type']] : $custom;
	}
	/* *
	 * 获取图片专辑
	 * @return array
	 */
	public static function getAlbum($params)
	{
		$prefix = Yii::app()->params['memcachePrefix'];
		$cacheExpire = isset(Yii::app()->params['memcacheLifeTime'])?Yii::app()->params['memcacheLifeTime']:60*5;
		$key = $prefix.'class'.$params['classId'].$params['accountId'].'album'.$params['albumId'].'@';
		Yii::app()->cache->delete($key);
		$album = Yii::app()->cache->get($key);
		if(!$album){
			$criteria = new CDbCriteria;
			$criteria->condition = "status = 'y' AND album_id=".$params['albumId']." AND garten_id=".$params['gartenId']." AND (find_in_set(".$params['accountId'].",object_ids) OR find_in_set(".$params['classId'].",class_ids))";
            $criteria->order = 'photo_id asc';
			$data = Photo::model()->findAll($criteria);
			foreach($data as $d){
				$album[] = array('id'=>$d['photo_id'],'album_id'=>$d['album_id'],'count'=>$d['count'],'path'=>$d['path'],'name'=>$d['name'],'remark'=>$d['remark'],'style'=>$d['style'],'sign'=>$d['sign'],'pub_address'=>$d['pub_address'],'in_time'=>$d['in_time']);
			}
			Yii::app()->cache->set($key, $album, $cacheExpire);
		}
		return $album;
	}

    /* *
     * 初始化及加载用户相关信息
     * @param array $params = array(
     *      'accountId'=>2,
     *      'classId'=>1,
     *      'page'=>1,
     *      'pagesize'=>20
     * );
     */
    public static function accountMsg($account_id)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        if(isset($params['page']) && !empty($params['page'])){
            $page = (int)$params['page'];
        }else{
            $page = 1;
        }
        if(isset($params['pagesize']) && !empty($params['pagesize'])){
            $pagesize = (int)$params['pagesize'];
        }else{
            $pagesize = 20;
        }
        $offset = ($page-1)*$pagesize;
        $key = $prefix.$account_id.'msg'.$page.'@';
        Yii::app()->cache->delete($key);
        $msg = Yii::app()->cache->get($key);
        if(!$msg){
        	$message_mid = MessageNew::model()->findAll(array('condition'=>"m_to={$account_id}"));
        	//echo $account_id;exit;
        	if(isset($message_mid)){
        		$msg_id_arr = array();
				$message_mid = ArrayUtil::getArraysAttributes($message_mid)?ArrayUtil::getArraysAttributes($message_mid):array();
				if(isset($message_mid)){
					foreach($message_mid as $v){
						$msg_id_arr[] = $v['md_id'];
					}
				}
			}

			$message_mid_str = "";
			if(!empty($msg_id_arr)){
			$message_mid_str = implode(",",$msg_id_arr);
			//print_r($message_mid);exit;
	    	$condition = "md_id in({$message_mid_str})";
	    	$criteria = new CDbCriteria;
            $criteria->condition = $condition;
            $criteria->order = 'md_time desc';
            $criteria->limit = $pagesize;
            $criteria->offset = $offset;
            $msg = MessageDetails::model()->findAll($criteria);
            Yii::app()->cache->set($key, $msg,$cacheExpire);
			}
        }
        return $msg;
    }

    /* *
     * 幼儿园职位数据缓存 //modify kuyuecs
     * 幼儿园职位缓存
     */
    public static function getPostCache($gartenId)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        $key = $prefix.$gartenId.'post'.'@';
        Yii::app()->cache->delete($key);
        $post = Yii::app()->cache->get($key);
        if(!$post){
            $data = Post::model()->findAll();
            if($data){
                foreach($data as $k=>$v){
                    $post[$v['post_id']] = array('post_id'=>$v['post_id'],'post_name'=>$v['post_name']);
                }
            }
            Yii::app()->cache->set($key, $post,$cacheExpire);
        }
        return $post;
    }


    /* *
     * 幼儿园部门缓存
     * @param int $gartenId
     * @return array
     */
    public static function getDepartmentCache($gartenId)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        $key = $prefix.$gartenId.'department'.'@';
        Yii::app()->cache->delete($key);
        $department = Yii::app()->cache->get($key);
        if(!$department){
            $criteria = new CDbCriteria;
            $criteria->condition = "garten_id='".$gartenId."' and status = 'y'";
            $criteria->order = 'in_time desc';
            $data = Department::model()->findAll($criteria);
            if($data){
                foreach($data as $d){
                    $department[$d['department_id']] = array('department_id'=>$d['department_id'],'department_name'=>$d['department_name'],'in_time'=>$d['in_time']);
                }
            }
            Yii::app()->cache->set($key, $department,$cacheExpire);
        }
        return $department;
    }

    /* *
     * 用户某天第N条信息开始的M条信息
     */
    public static function getGrowthCache($params)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        $key = $prefix.$params['accountId'].'growth'.$params['endTime'].$params['offset'].'@';
        Yii::app()->cache->delete($key);
        $growth = Yii::app()->cache->get($key);
        if(!$growth){
            $param = self::filterArray($params,array('accountId'=>'','classId'=>'','offset'=>'','endTime'=>'','total'=>''));
            $type = 'union';
            $offset = isset($param['offset']) && !empty($param['offset']) ? $param['offset'] : 0;
            $pagesize = isset($param['total']) && !empty($param['total']) ? $param['total'] : 20;
            $sql = "SELECT * FROM ".self::getSql($param,$type)." ORDER BY in_time DESC,album_id DESC limit ".$offset.",".$pagesize;
            $command = Yii::app()->db->createCommand($sql);
            $growth = $command->queryAll();
            Yii::app()->cache->set($key,$growth,$cacheExpire);
        }
        return $growth;
    }

    /* *
     * 初始化用户最近三天日志，图片，视频，录音信息中的第一页信息
     * @param array $params = array(
     *      'accountId'=>1，
     *      'startTime'=>12121,
     *      'endTime'=>24324
     * );
     */
    public static function accountInit($params)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        if(isset($params['page']) && !empty($params['page'])){
            $page = (int)$params['page'];
        }else{
            $page = 1;
        }
        if(isset($params['pagesize']) && !empty($params['pagesize'])){
            $pagesize = (int)$params['pagesize'];
        }else{
            $pagesize = 20;
        }
        $offset = ($page-1)*$pagesize;
        $key = $prefix.$params['accountId'].'growth'.$page.'start'.$offset.'@';
        Yii::app()->cache->delete($key);
        $growth = Yii::app()->cache->get($key);
        if(!$growth){
            $param = self::filterArray($params,array('accountId'=>'','classId'=>'','album'=>'','startTime'=>'','endTime'=>''));
            $type = 'union';
            $sql = "SELECT * FROM ".self::getSql($param,$type)." ORDER BY in_time DESC,album_id DESC limit ".$offset.",".$pagesize;
            $command = Yii::app()->db->createCommand($sql);
            $growth = $command->queryAll();
            Yii::app()->cache->set($key, $growth,$cacheExpire);
        }
        return $growth;
    }

    /* *
     * 统计时间轴上用户每月有数据的天数
     */
    public static function getStatisticsByMonth($params)
    {
        if(isset($params['accountId']) && !empty($params['accountId'])){
            $data = $tmp = array();
            $days = TrHelper::getTheMonth(F::now());
            $startTime = isset($params['startTime']) && !empty($params['startTime']) ? $params['startTime'] : strtotime($days[0]);
            $endTime = isset($params['endTime']) && !empty($params['endTime']) ? $params['endTime'] : strtotime($days[1]." 23:59:59");
            $accountId = $params['accountId'];

            //图片统计group by album_id
            $sql = "select in_time,count(*) as count from (select in_time,photo_id from ".Photo::model()->tableName()." where (find_in_set(".$accountId.",object_ids) or find_in_set(".$params['classId'].",class_ids)) and in_time>=".$startTime." and in_time<=".$endTime." AND status = 'y' group by album_id) AS P group by FROM_UNIXTIME(in_time,'%Y-%m-%d')";
            $command = Yii::app()->db->createCommand($sql);
            $p_rs = $command->queryAll();
            if($p_rs){
            	foreach($p_rs as $p){
            		$tmp[] = date("Y-m-d",$p['in_time']);
            		$data[date("Y-m-d",$p['in_time'])] = array('day'=>date("Y-m-d",$p['in_time']),'count'=>$p['count']);
            	}
            }

            $criteria =new CDbCriteria;
            $criteria->select = "in_time,count(*) as count";
            $criteria->condition = " status = 'y' AND (find_in_set(".$accountId.",object_ids) OR find_in_set(".$params['classId'].",class_ids)) AND in_time>=".$startTime." AND in_time<=".$endTime;
            $criteria->order = 'in_time desc';
            $criteria->group = 'FROM_UNIXTIME(in_time,"%Y-%m-%d")';

            //视频统计
            $video = new Video;
            $v_rs = $video->findAll($criteria);
            if($v_rs){
                foreach($v_rs as $v){
                    if(!in_array(date("Y-m-d",$v['in_time']), $tmp)){
                        $tmp[] = date("Y-m-d",$v['in_time']);
                        $data[date("Y-m-d",$v['in_time'])] = array('day'=>date("Y-m-d",$v['in_time']),'count'=>$v['count']);
                    }else{
                        $data[date("Y-m-d",$v['in_time'])]['count'] = $data[date("Y-m-d",$v['in_time'])]['count']+$v['count'];
                    }
                }
            }

            //录音统计
            $recorder = new Recorder;
            $r_rs = $recorder->findAll($criteria);
            if($r_rs){
                foreach($r_rs as $r){
                    if(!in_array(date("Y-m-d",$r['in_time']), $tmp)){
                        $tmp[] = date("Y-m-d",$r['in_time']);
                        $data[date("Y-m-d",$r['in_time'])] = array('day'=>date("Y-m-d",$r['in_time']),'count'=>$r['count']);
                    }else{
                        $data[date("Y-m-d",$r['in_time'])]['count'] = $data[date("Y-m-d",$r['in_time'])]['count']+$r['count'];
                    }
                }
            }

            //日志统计
            $log = new Log;
            $l_rs = $log->findAll($criteria);
            if($l_rs){
                foreach($l_rs as $l){
                    if(!in_array(date("Y-m-d",$l['in_time']), $tmp)){
                        $tmp[] = date("Y-m-d",$l['in_time']);
                        $data[date("Y-m-d",$l['in_time'])] = array('day'=>date("Y-m-d",$l['in_time']),'count'=>$l['count']);
                    }else{
                        $data[date("Y-m-d",$l['in_time'])]['count'] = $data[date("Y-m-d",$l['in_time'])]['count']+$l['count'];
                    }
                }
            }
            array_multisort($data,SORT_DESC);
            if(count($data)>0){
            	return $data;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    /* *
     * 获取当前数据相邻下一条数据时间
     * @return $year $month
     * @param array params = array(
     * 'loadTime'=>$loadTime,
     * 'accountId'=>$accountId,
     * 'classId'=>$classId
     * );
     */
    public static function getNextDataTime($params)
    {
    	$where = " WHERE status = 'y' AND (find_in_set(".$params['accountId'].",object_ids) OR find_in_set(".$params['classId'].",class_ids)) AND in_time<".$params['loadTime'];
    	$v_sql = "SELECT in_time FROM ".Video::model()->tableName().$where;
    	$r_sql = "SELECT in_time FROM ".Recorder::model()->tableName().$where;
    	$p_sql = "SELECT in_time FROM ".Photo::model()->tableName().$where;
    	$l_sql = "SELECT in_time FROM ".Log::model()->tableName().$where;
    	$sql = $v_sql." UNION ".$r_sql." UNION ".$p_sql." UNION ".$l_sql." ORDER BY in_time DESC limit 1";
    	$command = Yii::app()->db->createCommand($sql);
    	$data = $command->queryAll();
    	return $data;
    }


    /* *
     * 详细页专辑内容
     * @param array params= array(
     *      'accountId'=>$accountId,
     *      'endTime'=>33333,
     *      'album'=>$album
     * );
     */
    public static function getAlbumAbout($params)
    {
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        if(isset($params['page']) && !empty($params['page']))
        {
        	$page = intval($params['page']);
        }else{
        	$page = 1;
        }
        if(isset($params['pagesize']) && !empty($params['pagesize']))
        {
        	$pagesize = intval($params['pagesize']);
        }else{
        	$pagesize = 100;
        }
        $order = isset($params['order']) && !empty($params['order'])?$params['order']:'in_time DESC,album_id DESC';
        $offset = ($page-1)*$pagesize;
        $key = $prefix.$params['accountId'].'albumAbount'.$params['album'].$page.'start'.$offset.'@';
        Yii::app()->cache->delete($key);
        $albumAbount = Yii::app()->cache->get($key);
        if(!$albumAbount){
            $param = self::filterArray($params,array('accountId'=>'','classId'=>'','album'=>'','ud'=>'','startTime'=>'','endTime'=>''));
            $type = 'union';
            $sql = "SELECT id,album_id,count,account_id,path,remark,name,style,sign,pub_address,in_time FROM ".self::getSql($param,$type)." ORDER BY ".$order." limit ".$offset.",".$pagesize;
            $command = Yii::app()->db->createCommand($sql);
            $albumAbount = $command->queryAll();
            Yii::app()->cache->set($key, $albumAbount,$cacheExpire);
        }
        return $albumAbount;
    }

    /* *
     * 返回sql查询语句
     * @param array $params = array('accountId'=>,'startTime'=>,'endTime'=>);
     * @param string $type
     */
    public static function getSql($params,$type)
    {
        $where = '';
        $where .=  " WHERE status = 'y' AND (find_in_set(".$params['accountId'] .",object_ids) OR find_in_set(".$params['classId'] .",class_ids))";
        $where .= isset($params['startTime']) && !empty($params['startTime']) ? " and in_time>=".$params['startTime'] : '';
        $where .= isset($params['endTime']) && !empty($params['endTime']) ? " and in_time<=".$params['endTime'] : '';
        if(!empty($where) && substr($where,0,4)===' and'){
            $where = ' where '.substr($where,4);
        }

        $ud = "";
        if(isset($params['ud']) && $params['ud']=='next'){
        	$ud = " (SELECT * FROM ".Photo::model()->tableName()." ORDER BY photo_id ASC ) ";
        }
        if(isset($params['ud']) && $params['ud']=='prev'){
        	$ud = " (SELECT * FROM ".Photo::model()->tableName()." ORDER BY photo_id DESC ) ";
        }

        $album = " group by album_id";
        $v_sql = "SELECT video_id AS id,video_id as album_id,1 as count,account_id,path,remark,name,style,video_type AS atype,sign,pub_address,in_time,status FROM ".Video::model()->tableName().$where;
        $r_sql = "SELECT recorder_id AS id,recorder_id AS album_id,1 as count,account_id,path,remark,name,style,recorder_type AS atype,sign,pub_address,in_time,status FROM ".Recorder::model()->tableName().$where;
        $p_sql = "SELECT photo_id AS id,album_id,count(photo_id) as count,account_id,path,remark,name,style,photo_type AS atype,sign,pub_address,in_time,status FROM ".$ud.Photo::model()->tableName().$where.$album;
        $l_sql = "SELECT log_id AS id,log_id AS album_id,1 as count,account_id,account_id AS path,1 as remark,content AS name,style,log_type AS atype,sign,pub_address,in_time,status FROM ".Log::model()->tableName().$where;
        $sql = "(".$v_sql." UNION ".$r_sql." UNION ".$p_sql." UNION ".$l_sql.") AS t";
        return $sql;
    }



    /* *
    *
    * 过滤数组,获得想要的数组成员
    * @param array $targetArr 目标数组
    * @param array $stdArr 想要的数组状态
    */

    public static function filterArray($targetArr,$stdArr)
    {
        $outArray=array();
        foreach($stdArr as $key=>$value) {
            if(isset($targetArr[$key])) {
                $outArray[$key]=htmlspecialchars($targetArr[$key]);
            }
            elseif($value!="must") {
                continue;
            }
            else {
                return false;
            }
        }
        return $outArray;
    }

    /* *
     * 用户图片专辑id
     * @param int $accountId
     * @return int album_id
     */
    public static function getAlbumByAccount($accountId,$inTime = ''){
        $prefix = Yii::app()->params['memcachePrefix'];
        $cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
        $key = $prefix.$accountId.'album@';
        Yii::app()->cache->delete($key);
        $album = Yii::app()->cache->get($key);
        if(!$album){
            $criteria = new CDbCriteria();
            $criteria->order = "photo_id desc";
            $criteria->limit = 1;
            $rs = Photo::model()->findAll($criteria);
            if($rs){
                foreach($rs as $r){
                    $in_time = $r['in_time'];
                    $pre_album = $r['album_id'];
                    $account_id = $r['account_id'];
                }
                $s_e_dt = F::now()-$in_time;
                if(!empty($inTime)){
                	$tunnel = Photo::model()->find("account_id = '".$accountId."' and path = '".date('Ymd',$inTime)."'");
                	if(!empty($tunnel['album_id'])){
                		$album = $tunnel['album_id'];
                	}else{
                		$album = $pre_album+1;
                	}
                }else{
                	if($s_e_dt>60*5){
                		$album = $pre_album+1;
                	}else{
                		$album = $account_id===$accountId?$pre_album:$pre_album+1;
                	}
                }
            }else{
                $album = 1;
            }
        }
        Yii::app()->cache->set($key,$album,$cacheExpire);
        return $album;
    }

    /* *
     * 获取一条成长记录
     * @param array $params
     */
    public static function getGrowOne($params){
    	if(!isset($params['gid']) || !is_numeric($params['gid']) || !isset($params['sign']) || empty($params['sign'])){
    		return null;
    	}
    	$prefix = Yii::app()->params['memcachePrefix'];
    	$cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
    	$key = $prefix.$params['accountId'].'growthOne'.$params['gid'].'@';
    	Yii::app()->cache->delete($key);
    	$gdata = Yii::app()->cache->get($key);
    	if(!$gdata){
	    	$data = array();
	    	switch ($params['sign']){
	    		case 'p':
	    			$rs = Photo::model()->findByPk($params['gid']);
	    			if($rs){
	    				$data['releaseId'] = $rs->account_id;
	    				$data['id'] = $rs->photo_id;
	    				$data['album_id'] = $rs->album_id;
	    				$data['name'] = $rs->name;
	    				$data['remark'] = $rs->remark;
	    				$data['sign'] = $rs->sign;
	    				$data['style'] = $rs->style;
	    				$data['pub_address'] = $rs->pub_address;
	    				$data['in_time'] = $rs->in_time;
	    				$data['status'] = $rs->status;//kuyuecs 2012-07-24
	    				$data['atype'] = $rs->photo_type;//kuyuecs 2012-07-25
	    				$data['ud'] = isset($params['ud'])?$params['ud']:'';//kuyuecs 2013-05-06
	    				$criteria = new CDbCriteria;
	    				$criteria->select = 'count(photo_id) as count';
	    				$criteria->condition = "album_id=".$data['album_id'];
	    				$criteria->group = "album_id";
			            $criteria->limit = 1;
			            $album = Photo::model()->find($criteria);
			            $data['count'] = $album['count'];
	    			}
	    			break;
	    		case 'v':
	    			$rs = Video::model()->findByPk($params['gid']);
	    			if($rs){
	    				$data['releaseId'] = $rs->account_id;
	    				$data['id'] = $rs->video_id;
	    				$data['album_id'] = $rs->video_id;
	    				$data['name'] = $rs->name;
	    				$data['remark'] = $rs->remark;
	    				$data['sign'] = $rs->sign;
	    				$data['style'] = $rs->style;
	    				$data['pub_address'] = $rs->pub_address;
	    				$data['in_time'] = $rs->in_time;
	    				$data['status'] = $rs->status;//kuyuecs 20120724
	    				$data['atype'] = $rs->video_type;//kuyuecs 20120725
	    				$data['ud'] = isset($params['ud'])?$params['ud']:'';//kuyuecs 2013-05-06
	    				$data['count'] = 1;
	    			}
	    			break;
	    		case 'r':
	    			$rs = Recorder::model()->findByPk($params['gid']);
	    			if($rs){
	    				$data['releaseId'] = $rs->account_id;
	    				$data['id'] = $rs->recorder_id;
	    				$data['album_id'] = $rs->recorder_id;
	    				$data['name'] = $rs->name;
	    				$data['remark'] = $rs->remark;
	    				$data['sign'] = $rs->sign;
	    				$data['style'] = $rs->style;
	    				$data['pub_address'] = $rs->pub_address;
	    				$data['in_time'] = $rs->in_time;
	    				$data['status'] = $rs->status;//kuyuecs 20120724
	    				$data['atype'] = $rs->recorder_type;//kuyuecs 20120725
	    				$data['ud'] = isset($params['ud'])?$params['ud']:'';//kuyuecs 2013-05-06
	    				$data['count'] = 1;
	    			}
	    			break;
	    		case 'l':
	    			$rs = Log::model()->findByPk($params['gid']);
	    			if($rs){
	    				$data['releaseId'] = $rs->account_id;
	    				$data['id'] = $rs->log_id;
	    				$data['album_id'] = $rs->log_id;
	    				$data['name'] = $rs->content;
	    				$data['sign'] = $rs->sign;
	    				$data['style'] = $rs->style;
	    				$data['pub_address'] = $rs->pub_address;
	    				$data['in_time'] = $rs->in_time;
	    				$data['status'] = $rs->status;//kuyuecs 20120724
	    				$data['atype'] = $rs->log_type;//kuyuecs 20120725
	    				$data['ud'] = isset($params['ud'])?$params['ud']:'';//kuyuecs 2013-05-06
	    				$data['count'] = 1;
	    			}
	    			break;
	    		default:
	    			break;
	    	}
	    	$gdata = $data;
	    	Yii::app()->cache->set($key,$data,$cacheExpire);
    	}
    	return $gdata;
    }

    /* *
     * 幼儿园成长档案信息统计：有多少老师，每个老师对多少学生发布过信息，发布了多少信息
     * @param int $gartenId
     * @return array
     */
    public static function getGartenStatisticsByMonth($gartenId,$accountId='')
    {
    	$data = array();//返回的统计数据
    	$month = array();//有数据的年月份
    	$count = array('log'=>0,'video'=>0,'recorder'=>0,'photo'=>0,'teacher'=>0);//各个类型的记录总数
    	$teacher_ids = array();
    	$params = array('gartenId'=>$gartenId,'group'=>"month",'accountId'=>$accountId);
    	//幼儿园日志按月统计
    	$log_rs = GartenLogService::model()->getGartenLogByMonth($params);
    	if($log_rs){
    		foreach($log_rs as $v){
    			if(in_array(date('Y-m',$v['in_time']), $month)){
    				if(isset($data[date('Y-m',$v['in_time'])]['log']['count'])){
    					$data[date('Y-m',$v['in_time'])]['log']['count'] = $data[date('Y-m',$v['in_time'])]['log']['count']+$v['count'];
    				}else{
    					$data[date('Y-m',$v['in_time'])]['log']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$data[date('Y-m',$v['in_time'])]['log']['count'] = $v['count'];
    				$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    			$count['log'] += $v['count'];
    			if(!in_array($v['account_id'], $teacher_ids)){
    				array_push($teacher_ids,  $v['account_id']);
    			}
    		}
    	}
    	//幼儿园视频按月统计
    	$video_rs = VideoService::model()->getGartenVideoByMonth($params);
    	if($video_rs){
    		foreach($video_rs as $v){
    			if(in_array(date('Y-m',$v['in_time']), $month)){
    				if(isset($data[date('Y-m',$v['in_time'])]['video']['count'])){
    					$data[date('Y-m',$v['in_time'])]['video']['count'] = $data[date('Y-m',$v['in_time'])]['video']['count']+$v['count'];
    				}else{
    					$data[date('Y-m',$v['in_time'])]['video']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m',$v['in_time']);
    				$data[date('Y-m',$v['in_time'])]['video']['count'] = $v['count'];
    				$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    			$count['video'] += $v['count'];
    			if(!in_array($v['account_id'], $teacher_ids)){
    				array_push($teacher_ids,  $v['account_id']);
    			}
    		}
    	}
    	//幼儿园音频按月统计
    	$recorder_rs = RecorderService::model()->getGartenRecorderByMonth($params);
    	if($recorder_rs){
    		foreach($recorder_rs as $v){
    			if(in_array(date('Y-m',$v['in_time']), $month)){
    				if(isset($data[date('Y-m',$v['in_time'])]['recorder']['count'])){
    					$data[date('Y-m',$v['in_time'])]['recorder']['count'] = $data[date('Y-m',$v['in_time'])]['recorder']['count']+$v['count'];
    				}else{
    					$data[date('Y-m',$v['in_time'])]['recorder']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m',$v['in_time']);
    				$data[date('Y-m',$v['in_time'])]['recorder']['count'] = $v['count'];
    				$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    			$count['recorder'] += $v['count'];
    			if(!in_array($v['account_id'], $teacher_ids)){
    				array_push($teacher_ids,  $v['account_id']);
    			}
    		}
    	}
    	//幼儿园照片按月统计
    	$photo_rs = PhotoService::model()->getGartenPhotoByMonth($params);
    	if($photo_rs){
    		foreach($photo_rs as $v){
    			if(in_array(date('Y-m',$v['in_time']), $month)){
    				if(isset($data[date('Y-m',$v['in_time'])]['photo']['count'])){
    					$data[date('Y-m',$v['in_time'])]['photo']['count'] = $data[date('Y-m',$v['in_time'])]['photo']['count']+$v['count'];
    				}else{
    					$data[date('Y-m',$v['in_time'])]['photo']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m',$v['in_time']);
    				$data[date('Y-m',$v['in_time'])]['photo']['count'] = $v['count'];
    				$data[date('Y-m',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    			$count['photo'] += $v['count'];
    			if(!in_array($v['account_id'], $teacher_ids)){
    				array_push($teacher_ids,  $v['account_id']);
    			}
    		}
    	}
    	/* $array_key = array_keys($data);
    	$array_value = array_values($data);
    	$array_return = array();
    	for($i=1, $size_of_array=sizeof($array_key);$i<=$size_of_array;$i++){
    		$array_return[$array_key[$size_of_array-$i]] = $array_value[$size_of_array-$i];
    	} */
    	return array('data'=>$data,'count'=>$count,'teacher_ids'=>$teacher_ids);
    }

    /* *
     * 幼儿园成长档案信息统计：有多少老师，每个老师对多少学生发布过信息，发布了多少信息
     * @param array $params
     * @return array
     */
    public static function getGartenByMonthDetail($params)
    {
    	$data = array();//返回的统计数据
    	$month = array();//有数据的年月份
    	//幼儿园日志按月统计
    	$log_rs = GartenLogService::model()->getGartenLogByMonth($params);
    	if($log_rs){
    		foreach($log_rs as $v){
    			if(in_array(date('Y-m-d',$v['in_time']), $data)){
    				if(isset($data[date('Y-m-d',$v['in_time'])]['log']['count'])){
    					$data[date('Y-m-d',$v['in_time'])]['log']['count'] = $data[date('Y-m-d',$v['in_time'])]['log']['count']+$v['count'];
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['log']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m-d',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m-d',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m-d',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m-d',$v['in_time']);
    				$data[date('Y-m-d',$v['in_time'])]['log']['count'] = $v['count'];
    				$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    		}
    	}
    	//幼儿园视频按月统计
    	$video_rs = VideoService::model()->getGartenVideoByMonth($params);
    	if($video_rs){
    		foreach($video_rs as $v){
    			if(in_array(date('Y-m-d',$v['in_time']), $month)){
    				if(isset($data[date('Y-m-d',$v['in_time'])]['video']['count'])){
    					$data[date('Y-m-d',$v['in_time'])]['video']['count'] = $data[date('Y-m-d',$v['in_time'])]['video']['count']+$v['count'];
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['video']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m-d',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m-d',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m-d',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m-d',$v['in_time']);
    				$data[date('Y-m-d',$v['in_time'])]['video']['count'] = $v['count'];
    				$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    		}
    	}
    	//幼儿园音频按月统计
    	$recorder_rs = RecorderService::model()->getGartenRecorderByMonth($params);
    	if($recorder_rs){
    		foreach($recorder_rs as $v){
    			if(in_array(date('Y-m-d',$v['in_time']), $month)){
    				if(isset($data[date('Y-m-d',$v['in_time'])]['recorder']['count'])){
    					$data[date('Y-m-d',$v['in_time'])]['recorder']['count'] = $data[date('Y-m-d',$v['in_time'])]['recorder']['count']+$v['count'];
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['recorder']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m-d',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m-d',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m-d',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m-d',$v['in_time']);
    				$data[date('Y-m-d',$v['in_time'])]['recorder']['count'] = $v['count'];
    				$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    		}
    	}
    	//幼儿园图片按月统计
    	$photo_rs = PhotoService::model()->getGartenPhotoByMonth($params);
    	if($photo_rs){
    		foreach($photo_rs as $v){
    			if(in_array(date('Y-m-d',$v['in_time']), $month)){
    				if(isset($data[date('Y-m-d',$v['in_time'])]['photo']['count'])){
    					$data[date('Y-m-d',$v['in_time'])]['photo']['count'] = $data[date('Y-m-d',$v['in_time'])]['photo']['count']+$v['count'];
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['photo']['count'] = $v['count'];
    				}
    				if(isset($data[date('Y-m-d',$v['in_time'])]['tIds']) && !in_array($v['account_id'], $data[date('Y-m-d',$v['in_time'])]['tIds'])){
    					array_push($data[date('Y-m-d',$v['in_time'])]['tIds'], $v['account_id']);
    				}else{
    					$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    				}
    			}else{
    				$month[] = date('Y-m-d',$v['in_time']);
    				$data[date('Y-m-d',$v['in_time'])]['photo']['count'] = $v['count'];
    				$data[date('Y-m-d',$v['in_time'])]['tIds'] = array($v['account_id']);
    			}
    		}
    	}
    	return $data;
    }

    /* *
     * 幼儿园每天教师发布成长信息的详细记录
     * @param array $params
     */
    public static function getGartenByDayDetail($params)
    {
    	$data = array();
    	$teacher_ids = array();
    	$student_ids = array();
    	if(isset($params['type'],$params['gartenId'],$params['startTime'],$params['endTime'])){
    		$type = $params['type'];
    		if(strstr($type,'l')){
    			$log_rs = GartenLogService::model()->getGartenLogByDay($params);
    			if($log_rs){
    				foreach($log_rs as $v){
    					$data['data'][] = $v;

    				}
    			}
    		}
    		if(strstr($type,'v')){
    			$video_rs = VideoService::model()->getGartenVideoByDay($params);
    			if($video_rs){
    				foreach($video_rs as $v){
    					$data['data'][] = $v;
    				}
    			}
    		}
    		if(strstr($type,'r')){
    			$recorder_rs = RecorderService::model()->getGartenRecorderByDay($params);
    			if($recorder_rs){
    				foreach($recorder_rs as $v){
    					$data['data'][] = $v;
    				}
    			}
    		}
    		if(strstr($type,'p')){
    			$photo_rs = PhotoService::model()->getGartenPhotoByDay($params);
    			if($photo_rs){
    				foreach($photo_rs as $v){
    					$data['data'][] = $v;
    				}
    			}
    		}
    		if(!empty($data['data'])){
    			foreach($data['data'] as $k=>$v){
    				$studentCount = 0;
    				$studentTmp = array();
    				if(strstr($v['object_ids'],',')){
    					$student_tmp = explode(',', $v['object_ids']);
    					foreach($student_tmp as $t){
    						if(!in_array($t,$studentTmp)){
    							$studentCount = $studentCount+1;
    							array_push($studentTmp, $t);
    						}
    						if(!in_array($t,$student_ids)){
    							array_push($student_ids, $t);
    						}
    					}
    				}elseif(!empty($v['object_ids'])){
    					if(!in_array($v['object_ids'],$student_ids)){
    						array_push($student_ids, $v['object_ids']);
    					}
    					if(!in_array($v['object_ids'],$studentTmp)){
    						$studentCount = $studentCount+1;
    						array_push($studentTmp, $v['object_ids']);
    					}
    				}
    				$class_ids = array();
    				if(empty($v['object_ids']) && empty($v['class_ids'])){
    					array_push($teacher_ids, $v['account_id']);
    				}
    				if(strstr($v['class_ids'],',')){
    					$class_tmp = explode(',', $v['class_ids']);
    					foreach($class_tmp as $c){
    						if(!in_array($c,$class_ids)){
    							array_push($class_ids, $c);
    						}
    					}
    				}else{
    					if(!in_array($v['class_ids'],$class_ids)){
    						array_push($class_ids, $v['class_ids']);
    					}
    				}
    				if(!empty($class_ids)){
    					$rs = AccountService::model()->getGartenAccountByClass($class_ids);
    					if($rs){
	    					foreach($rs as $r){
	    						if(!in_array($r['account_id'],$studentTmp)){
	    							$studentCount = $studentCount+1;
	    							array_push($studentTmp, $r['account_id']);
	    						}
	    					}
    					}
    				}
    				$data['data'][$k]['count'] = $studentCount;
    				if(!in_array($v['account_id'],$teacher_ids)){
    					array_push($teacher_ids, $v['account_id']);
    				}
    			}
    		}
    		if(!empty($teacher_ids)){
    			$data['teacher'] = AccountService::model()->getGartenAccountByIds($teacher_ids);
    		}
    		if(!empty($student_ids)){
    			$data['student'] = AccountService::model()->getGartenAccountByIds($student_ids);
    		}
    	}
    	return $data;
    }

	/* *
	 * @params array
	 * @return data
	 * 健康成长坐标值
	 */
	public static function getGrowupHealthAxis($params)
	{
		$prefix = Yii::app()->params['memcachePrefix'];
		$cacheExpire = isset(Yii::app()->params['memcacheLifetime'])?Yii::app()->params['memcacheLifetime']:60*5;
		$studentId = isset($params['studentId'])?$params['studentId']:'';
		$key = $prefix.'studentId'.$studentId.'@';
		Yii::app()->cache->delete($key);
		$healthAxis = Yii::app()->cache->get($key);
		if(!$healthAxis){
			$condition = !empty($studentId)?"student_id='".$studentId."'":'';
			$condition .= (isset($params['beforeTime']) && !empty($params['beforeTime']))?" and add_time <= '".date('Y-m-d').' 23:59:59'."' and add_time >= '".date('Y-m-d',$params['beforeTime']).' 00:00:00'."' ":'';
			$criteria = new CDbCriteria;
			$criteria->select = 'account_id,parent_id,height,weight,add_time';
			$criteria->condition = $condition;
			$criteria->order = 'add_time asc';
			$data = StudentHealthinfo::model()->findAll($criteria);
			$mul = '';
			$interval = (isset($params['interval']) && !empty($params['interval']))?$params['interval']:12;
			if(!empty($data)){
				foreach($data as $v){
					$return[] = array('account_id'=>$v['account_id'],'parent_id'=>$v['parent_id'],'height'=>$v['height'],'weight'=>$v['weight'],'add_time'=>$v['add_time']);
				}
				if(count($return)<=$interval){
					$healthAxis = $return;
				}else{
					$mul = round(count($return)/$interval,1);
					for($i=0;$i<$interval;$i++){
						$healthAxis[] = $return[floor($mul*$i)];
					}
					$healthAxis[10] = $return[count($return)-2];
					$healthAxis[11] = $return[count($return)-1];
				}
			}
			Yii::app()->cache->set($key, $healthAxis, $cacheExpire);
		}
		return $healthAxis;
	}
}
?>
