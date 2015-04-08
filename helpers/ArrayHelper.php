<?php

namespace matacms\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper {

	public static function divide($array, $segmentCount) {
		$listlen = count($array);
		$partlen = floor($listlen / $segmentCount);
		$partrem = $listlen % $segmentCount;
		$partition = array();
		$mark = 0;
		for($px = 0; $px < $segmentCount; $px ++) {
			$incr = ($px < $partrem) ? $partlen + 1 : $partlen;
			$partition[$px] = array_slice($array, $mark, $incr);
			$mark += $incr;
		}
		return $partition;
	}

}