<?php


// 10 de Octubre del 2014
// Model.php
// @brief agrego la clase Model para reducir las lineas de los modelos

class Model {

	public static function exists($modelname){
		$fullpath = self::getFullpath($modelname);
		$found=false;
		if(file_exists($fullpath)){
			$found = true;
		}
		return $found;
	}

	public static function getFullpath($modelname){
		return "core/app/model/".$modelname.".php";
	}

<<<<<<< HEAD
	public static function many($query,$aclass){
=======
	public static function many($query, $aclass){
>>>>>>> 84363076d9f818cf8dd78b32db6cc5734582484e
		$cnt = 0;
		$array = array();
		while($r = $query->fetch_array()){
			$array[$cnt] = new $aclass;
<<<<<<< HEAD
			$cnt2=1;
			foreach ($r as $key => $v) {
				if($cnt2>0 && $cnt2%2==0){ 
=======
			$cnt2 = 1;
			foreach ($r as $key => $v) {
				if ($cnt2 > 0 && $cnt2 % 2 == 0) { 
>>>>>>> 84363076d9f818cf8dd78b32db6cc5734582484e
					$array[$cnt]->$key = $v;
				}
				$cnt2++;
			}
			$cnt++;
		}
		return $array;
	}
	//////////////////////////////////
	public static function one($query,$aclass){
		$cnt = 0;
		$found = null;
		$data = new $aclass;
		while($r = $query->fetch_array()){
			$cnt=1;
			foreach ($r as $key => $v) {
				if($cnt>0 && $cnt%2==0){ 
					$data->$key = $v;
				}
				$cnt++;
			}

			$found = $data;
			break;
		}
		return $found;
	}

}



?>