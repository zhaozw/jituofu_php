<?php
/**
 * @redis存储
 * @author wanghui
 * @date 2013-12-30
 */
class Ch_redis
{
	private static $instance = array();

	protected $handler ;
	protected $options = null;

	private function __construct($config)
	{
		//获取配置列表里的redis配置
		$options =  Yii::app()->params->redis['servers']['default'];
		$this->handler = new Redis();
		$func = $options['persistent'] ? 'pconnect' : 'connect';
		if($options['timeout'] === false)
		{
			$this->handler->$func($options['host'],$options['port']);
		}
		else
		{
			$this->handler->$func($options['host'],$options['port'],$options['timeout']);
		}
		if(!empty($options['auth']))
		{
			$this->handler->auth($options['auth']);
		}
		if(isset($options['db']) && $options['db'] != 0)
		{
			$this->handler->select($options['db']);
		}
	}

	public static function getInstance($name='')
	{
		if (empty(self::$instance[$name]))
		{
			self::$instance[$name] = new Ch_redis($name);
		}

		return self::$instance[$name];
	}

	/**
	 *
	 */
	public function __call($method,$args)
	{
		return call_user_func_array(array($this->handler,$method),$args);
	}
	/**
	 * @desc  把返回给终端的数据存储在redis里，方便下次从redis里取
	 * @param   int    $pid  广告位ID
	 * @param   array  $directional   接收终端各种定向条件，并经过平台修正后的约定数组
	 * @param   int    $aid   广告ID
	 * @param   str    $material_url     广告物料的地址
	 * @param   str    $material_type      广告物料的文件类型
	 * @param   int    $expire
	 * @return  mixed
	 * @author  wanghui
	 * @date    2014-1-3
	 */
	public static function adSetRedis($pid,$directional,$aid,$material_url,$material_type,$expire)
	{
		if(intval($pid)>0 && intval($aid)>0){
			$redis = self::getInstance();
			$h = $set_key = $hash_key = '';
			$h = json_encode($directional);
			$hash_key = $aid.':'.$material_type;
			$set_key = $pid.':'.$h;
			$redis->hSet($h, $hash_key, $material_url);
			$redis->set($set_key, json_encode($redis->hGetAll($h)));
			$redis->setTimeout($set_key, $expire);
			$redis->setTimeout($h, $expire);
			return $redis->get($set_key);
		}
		return FALSE;
	}
	/**
	 * @desc    接收终端各种定向条件，并根据条件来得查询redis是否有存储该广告位的信息数据有
	 * @param   int   $pid  广告位ID
	 * @param   array $directional 各种定向条件组成的数组
	 * @return  mixed
	 * @author  wanghui
	 * @date    2014-1-3
	 */
	public static function getAdredisData($pid,$directional)
	{
		if(intval($pid)>0){
			$redis_return_data = array();
			$redis = self::getInstance();
			$set_key = $pid.':'.json_encode($directional);
			if($redis->exists($set_key)){
				$key_num = '';
				$key_num = $redis->keys($pid.':*');
				if(count($key_num)>1){
					foreach($key_num as $num){
						$num = strpos($num,':');
						$hash_key_tem = substr($num,$num+1);
						$redis_return_data[] = $redis->hGetAll($hash_key_tem);
					}
					return $redis_return_data;
				}
				$num = strpos($key_num[0],':');
				$hash_key_tem = substr($key_num[0],$num+1);
				return $redis->hGetAll($hash_key_tem);
			}
			return FALSE;
		}
		return FALSE;
	}
	/**
	 * @desc   当mysql数据更新时同步redis数据
	 * @param  int $pid   广告位ID
	 * @param  int $aid   广告ID
	 * @param  str  $material_old_url  修改之前的物料址
	 * @param  str  $material_new_url  要修改后的物料地址
	 * @param  int  $ad_endtime        广告的结束时间
	 * @return  boolean
	 * @author  wanghui
	 * @date    2014-1-3
	 */
	public static function SynchronousData($pid,$aid,$material_old_url,$material_new_url,$ad_endtime)
	{
		if(intval($pid) > 0 && intval($aid)>0){
			$expire = $ad_endtime - time();
			if($key_num = $redis->keys($pid.':*')){
				if(count($key_num)>1){
					foreach($key_num as $num){
						$num = strpos($num,':');
						$hash_key_tem = substr($num,$num+1);
						$redis->keys($hash_key_tem) && $hash_value = $redis->hGetAll($hash_key_tem);
						foreach($hash_value as $k=>$v){
							if($v==$material_old_url){
								$redis->hSet($hash_key_tem, $k, $material_new_url);
								$redis->setTimeout($hash_key_tem, $expire);
							}
						}
						$redis->set($num, json_encode($redis->hGetAll($hash_key_tem)));
					}
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	/**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function set($key, $value, $timeOut = 0) {
        $value = json_encode($value, TRUE);
        $retRes =$this->handler->set($key, $value);
        if ($timeOut > 0)$this->handler->setTimeout($key, $timeOut);
        return $retRes;
    }

    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key) {
        $result =$this->handler->get($key);
        return json_decode($result, TRUE);
    }

    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key) {
        return$this->handler->delete($key);
    }

    /**
     * 清空数据
     */
    public function flushAll() {
        return$this->handler->flushAll();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = true) {
        $value = json_encode($value);
        return $right ?$this->handler->rPush($key, $value) :$this->handler->lPush($key, $value);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        $val = $left ?$this->handler->lPop($key) :$this->handler->rPop($key);
        return json_decode($val);
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key) {
        return$this->handler->incr($key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        return$this->handler->decr($key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key) {
        return$this->handler->exists($key);
    }



    //===========================================================第二种方案====================================================//
    /**
	 * @desc  把返回给终端的数据存储在redis里，方便下次从redis里取*****同时也适用于操作MYSQL对数据的修改共用这个接口
	 * @param   int     $pid  广告位ID
	 * @param   int     $aid   方位ID
	 * @param   array   $ad_info    广告基本信息
	 * @param   array   $material_info     广告物料信息
	 * @param   array   $directional_info      广告的定向信息
	 * @param   int     $expire             广告过期时间
	 * @return  mixed
	 * @author  wanghui
	 * @date    2014-1-3
	 */
    public function addSetRedis($pid,$aid,$ad_info,$material_info,$directional_info,$expire)
    {
    	if(intval($pid)>0 && intval($aid)>0){
    		$key = '';
    		$key = $pid.':'.$aid;
    		$val = json_encode(array('ad_info'=>$ad_info,'material_info'=>$material_info,'directional_info'=>$directional_info));
    		$redis = self::getInstance();
    		$redis->set($key,$val);
    		$redis->setTimeout($key, $expire);
    		return $redis->get($key);
    	}
    	return FALSE;
    }
    /**
     * @desc    接收终端各种定向条件，并根据条件来得查询redis是否有存储该广告位的信息数据有
     * @param   int $pid 广告位ID
     * @return  mixed
     * @author  wanghui
     * @date    2014-1-3
     */
    public static function getAdSetRedis($pid)
    {
    	if(intval($pid)>0){
    		$redis_return_data = array();
			$redis = self::getInstance();
			if($key_num = $redis->keys($pid.':*')){
				if(count($key_num)>0){
					foreach($key_num as $num){
						$redis_return_data[$num] = $redis->get($num);
					}
				}
				return $redis_return_data;
			}
			return FALSE;
    	}
    	return FALSE;
    }
    /**
     * @desc  和MYSQL同步删除redis存储的广告数据
     * @param  int $pid
     * @param  int $aid
     * @return  boolean
     * @author  wanghui
     * @date   2014-1-3
     */
    public static function delRedisData($pid,$aid)
    {
    	if(intval($pid)>0 && intval($aid)>0){
    		$redis = self::getInstance();
    		if($redis->del($pid.':'.$aid)) return TRUE;
    	}
    	return FALSE;
    }
    /**
     * @desc redis数据恢复
     * @return  boolean
     * @author  wanghui
     * @date  2014-1-14
     */
	public static function mysqlLoadRedis(){
		$cur_time = time();
		$data = array();
		$aids = AdService::model()->getAvertisingAd($cur_time);
		if(!empty($aids)){
			foreach($aids as $aid){
				$pid = CrontabService::model()->getPidByAid($aid['aid']);
				if($pid !=NULL){
					foreach($pid as $item_id){
						$pidoldlist[] = $item_id['ad_position_id'];
					}
					$newAdpositionIds = $pidoldlist;
					if(AdService::updatePidAidInRedis($aid['aid'], $pidoldlist, $newAdpositionIds)){
						$data[$aid['aid']] = $aid['aid']."的广告存储成功！";
					}else{
						$data[$aid['aid']] = $aid['aid']."的广告存储不成功！";
					}
				}
			}
			return $data;
		}
		return $data;
	}
}

?>
