<?php
class NumericUtil {
	
	public static function conversionBirthdayToAge($birthday,$add=0){
		$age = date('Y')-date('Y',$birthday);
		return $age>0?($age+$add):0;
	}
}