<?php
/**
 * Sql 语句组装 常用方法
 * @author zhangzhicheng we3ew.com
 * @time: 2013-3-22-下午5:08:18
 */
class SqlUtil {
	
	/**
	 * 综合条件
	 * @param array $conditions
	 * @param string $implodeBy
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function buildCondition(array $conditions,$implodeBy='and',$split=false){
		$sqlArr = array();
		foreach ($conditions as $type => $value){
			if($type=='equal')
				$sqlArr[] = self::buildEqualCondition($value);
			if($type=='between')
				$sqlArr[] = self::buildEqualCondition($value);
			if($type=='orderby')
				$sqlArr[] = self::buildOrderCondition($value);
			if($type=='limit')
				$sqlArr[] = self::buildLimitCondition(isset($value['limit'])?$value['limit']:'', isset($value['offset'])?$value['offset']:'');
			
		}
		$sqlArr = ArrayUtil::removeEmptyClum($sqlArr);
		return self::sqlImplode($sqlArr,$implodeBy,$split);
	}
	
	/**
	 * 相等条件
	 * @param array $conditions
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function buildEqualCondition(array $conditions,$implodeBy='and',$split=false,$skip=true){
		$sqlArr = array();
		foreach ($conditions as $key=>$value){
			if($skip==true && $value==0)
				continue;
			if(is_array($value)){
				$sqlArr[] = self::buildInCondition(array($key=>$value));
			}else{
				$sqlArr[] = "`".$key."`='".$value."'";
			}
		}
		return self::sqlImplode($sqlArr,$implodeBy,$split);
	}
	

	/**
	 * 在2个数值之间
	 * $value['bt']:大于
	 * $value['lt']:小于
	 * array $conditions=array('time'=>array('bt'>123,'lt'<256));
	 * @param array $conditions
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function buildBetweenCondition(array $conditions,$implodeBy='and',$split=false){
		$sqlArr = array();
		foreach ($conditions as $key=>$value){
			if($value['bt']>0 && $value['lt']>0)
				$sqlArr[] = $key.">".$value['bt']." AND ".$key."<".$value['lt'];
			elseif($value['bt']>0 && $value['lt']==0)
				$sqlArr[] = $key.">".$value['bt'];
			elseif($value['bt']==0 && $value['lt']>0)
				$sqlArr[] = $key."<".$value['lt'];
			else 
				return null;
		}
		return self::sqlImplode($sqlArr,$implodeBy,$split);
	}
	
	/**
	 * IN查找
	 * @param array $conditions
	 * @param string $implodeBy
	 * @author zhangzhicheng
	 */
	public static function buildInCondition(array $conditions,$implodeBy='and',$split=false,$skip=true){
		$sqlArr = array();
		foreach ($conditions as $key=>$value){
			if($skip==true && ($value==0  || empty($value) || $value[0]==0) )
				continue;
			$sqlArr[] = "`".$key."` IN (".implode(',', $value).")";
		}
		return self::sqlImplode($sqlArr,$implodeBy,$split);
	}

	/**
	 * FIND_IN_SET 查找
	 * array('id'=>array(1,2,3))
	 * @param array $conditions
	 * @param string $implodeBy
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function buildFindInSetCondition(array $conditions,$implodeBy='or',$split=false,$skip=true){
		$sqlArr = array();
		foreach ($conditions as $key=>$values){
			if($skip==true && $values==0)
				continue;
			if(is_array($values))
			foreach ($values as $value){
				if($skip==true && $value==0)
					continue;
				$sqlArr[] = "FIND_IN_SET('".$value."',`".$key."`)";
			}
		}
		return self::sqlImplode($sqlArr,$implodeBy,$split);
	}
	
	
	/**
	 * ORDER BY 排序条件
	 * array('id'=>'desc','time'=>'asc');
	 * @param array $conditions
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function buildOrderCondition(array $conditions){
		if(empty($conditions))
			return null;
		$sqlArr = array();
		foreach ($conditions as $key=>$value){
			$sqlArr[] = $key.' '.$value;
		}
		return ' ORDER BY '.self::sqlImplode($sqlArr,',');
	}
	
	/**
	 * LIMIT OFFSET 数量条件
	 * @param int $limit
	 * @param int $offset
	 * @return NULL|string
	 * @author zhangzhicheng
	 */
	public static function buildLimitCondition($limit,$offset){
		if(empty($limit) && empty($offset))
			return null;
		if(!empty($limit) && empty($offset))
			return ' LIMIT '.$limit;
		if(empty($limit) && !empty($offset))
			return ' OFFSET '.$offset;
		return ' LIMIT '.$limit.' OFFSET '.$offset;
	}
	

	/**
	 * GROUP BY 条件添加
	 * @param array $conditions
	 * @return NULL|string
	 * @author zhangzhicheng
	 */
	public static function buildGroupCondition(array $conditions){
		if(empty($conditions))
			return null;
		return " GROUP BY `".implode("`,`", $conditions)."`";
	}
	
	/**
	 * 数组 拼字符串
	 * @param array $sqlArr
	 * @param string $implodeBy
	 * @return string
	 * @author zhangzhicheng
	 */
	public static function sqlImplode(array $sqlArr,$implodeBy='and',$split=false){
		$implodeBy= strtoupper($implodeBy)=='OR'?' OR ':(strtoupper($implodeBy)=='AND'?' AND ':$implodeBy);
		$sqlArr = ArrayUtil::removeEmptyClum($sqlArr);
		if(empty($sqlArr))
			return '';
		$sql = implode($implodeBy, $sqlArr);
		$sql = $split==false?$sql:('('.$sql.')');
		return $sql;
	}
	
	
}