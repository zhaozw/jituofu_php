<?php
class AdminUtil {

	
	public static function getTypeName($type){
		switch ($type) {
			case 'a':
				$name = '超级管理员';
				break;
			case 'sm':
				$name = '销售总监';
				break;
			case 's':
				$name = '销售员';
				break;
			case 'cm':
				$name = '客服经理';
				break;
			case 'cs':
				$name = '客服人员';
				break;
			case 'v':
				$name = '教育部门';
				break;
			default:
				$name = '教育部门';
				break;
		}
		return $name;
	}
}