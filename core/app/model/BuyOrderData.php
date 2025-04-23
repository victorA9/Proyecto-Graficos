<?php
class BuyOrderData {

	public static $tablename = "buy_orders";


	public function BuyOrderData(){
		$this->pdf_path = "";
		$this->total = 0.0;
		$this->status = "pendiente";
		$this->created_by = null;
		$this->created_at = "NOW()";
        $this->signed_at = null;
	}

	public static function add(){
		$sql = "insert into ".self::$tablename." (pdf_path, total, created_by) ";
		$sql .= "values (\"$this->pdf_path\", $this->total, $this->created_by)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto BrandData previamente utilizamos el contexto
	public function signOrder($id){
		$sql = "update ".self::$tablename." set status=\"firmada\", signed_at=NOW() where id=$id";
		Executor::doit($sql);
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new BuyOrderData());
	}

}

?>