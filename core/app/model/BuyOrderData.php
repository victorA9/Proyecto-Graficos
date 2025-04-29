<?php
require_once __DIR__ . '/../../../libs/pdfparser-master/pdfparser-autoload.php';
use Smalot\PdfParser\Parser;

class BuyOrderData {


	public static $tablename = "buy_orders";

	const STATUS_PENDING = "pendiente";
	const STATUS_SIGNED = "firmada";
	const STATUS_PROCESSED = "procesada";
	const STATUS_COMPLETED = "completada";

	const DELIVERY_PENDING = "pendiente";
    const DELIVERY_DELIVERED = "entregado";

    const PAYMENT_PENDING = "pendiente";
    const PAYMENT_PAID = "pagado";

	public function __construct(){
		$this->pdf_path = "";
		$this->total = 0.0;
		$this->status = self::STATUS_PENDING;
		$this->created_by = null;
        $this->signed_at = null;
		$this->delivery_status = self::DELIVERY_PENDING;
		$this->payment_status = self::PAYMENT_PENDING;
	}


	public static function extractTotalFromPDF($filePath) {
		try {
			$parser = new Parser();
			$pdf = $parser->parseFile($filePath);
			$text = $pdf->getText();
			
			// Patrones comunes para detectar totales (ajusta según tus PDFs)
			$patterns = [
				'/^(?!.*Subtotal)Total\s*\$\s*([\d,]+\.\d{2})/im',
			];
			
			foreach ($patterns as $pattern) {
				if (preg_match($pattern, $text, $matches)) {
					return floatval(str_replace(',', '', $matches[1]));
				}
			}
			
			return 0.0; // Si no encuentra el total
		} catch (Exception $e) {
			error_log("Error leyendo PDF: ".$e->getMessage());
			return 0.0;
		}
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (pdf_path, total, status, created_by) ";
		$sql .= "values (\"$this->pdf_path\", $this->total, \"$this->status\", $this->created_by)";
		Executor::doit($sql);

		// Consulta separada para obtener el último ID
		$sql_id = "SELECT LAST_INSERT_ID() as id";
		$query = Executor::doit($sql_id);
		$row = $query[0]->fetch_assoc();
		return $row['id'];
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}

	public function signOrder($id, $userId){
		$sql = "update ".self::$tablename." set status=\"".self::STATUS_SIGNED."\", signed_at=NOW(), signed_by=$userId where id=$id";
		Executor::doit($sql);
	}

	public function processOrder($id) {
		$sql = "UPDATE ".self::$tablename." SET status=\"".self::STATUS_PROCESSED."\" WHERE id=$id";
		Executor::doit($sql);
	}

	public function completeOrder($id) {
		$sql = "UPDATE ".self::$tablename." SET status=\"".self::STATUS_COMPLETED."\" WHERE id=$id";
		Executor::doit($sql);
	}

	public function updateDeliveryStatus($id, $status) {
		$sql = "UPDATE " . self::$tablename . " SET delivery_status=\"$status\" WHERE id=$id";
		Executor::doit($sql);
	}

	public function updatePaymentStatus($id, $status) {
		$sql = "UPDATE " . self::$tablename . " SET payment_status=\"$status\" WHERE id=$id";
		Executor::doit($sql);
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new BuyOrderData());
	}

	public static function getByStatus($statuses) {
		// Si $statuses no es un array, conviértelo en uno
		if (!is_array($statuses)) {
			$statuses = [$statuses];
		}
	
		// Escapa los valores para evitar inyección SQL
		$escapedStatuses = array_map(function($status) {
			return '"' . addslashes($status) . '"';
		}, $statuses);
	
		// Construye la cláusula IN
		$statusList = implode(',', $escapedStatuses);
	
		// Construye la consulta
		$sql = "SELECT * FROM " . self::$tablename . " WHERE status IN ($statusList)";
		$query = Executor::doit($sql);
	
		return Model::many($query[0], new BuyOrderData());
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = $id LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new BuyOrderData());
	}

	public function getUser(){
		return UserData::getById($this->created_by);


	}
}

?>