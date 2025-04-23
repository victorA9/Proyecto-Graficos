<?php
// savebuyorder-action.php
session_start();

// 1. Verificar autenticación y permisos
if(!isset($_SESSION["user_id"])) {
    header("Location: index.php?view=login");
    exit;
}

// 2. Procesar subida de PDF (único requisito)
if(isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["error"] == 0) {
    // Configuración de upload
    $target_dir = "storage/orders/";
    $pdf_name = "order_".time()."_".$_SESSION["user_id"].".pdf";
    $target_file = $target_dir . basename($pdf_name);
    
    // Validar que sea PDF
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if($file_type != "pdf") {
        header("Location: index.php?view=newbuyorder&error=invalidpdf");
        exit;
    }

    // Mover archivo
    if(move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
        // 3. Crear la orden (solo con PDF)
        $order = new BuyOrderData();
        $order->pdf_path = $target_file;
        $order->total = 5.0; // se detecta automaticamente con el lector de pdfs
        $order->created_by = $_SESSION["user_id"];
        $order_id = $order->add();

        // 4. Redirigir a vista de detalle
        header("Location: index.php?view=onebuyorder&id=".$order_id);
    } else {
        header("Location: index.php?view=newbuyorder&error=upload");
    }
} else {
    header("Location: index.php?view=newbuyorder&error=nofile");
}
?>