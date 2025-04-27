<?php
// savebuyorder-action.php

// 1. Verificar autenticación y permisos
if(!isset($_SESSION["user_id"])) {
    header("Location: index.php?view=login");
    exit;
}

// 2. Procesar subida de PDF (único requisito)
if(isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["error"] == 0) {
    // 1. Definir ruta ABSOLUTA (más confiable)
    $target_dir = __DIR__ . "/../../storage/orders/"; // __DIR__ = ruta actual del script

// 2. Crear carpeta si no existe (con permisos)
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // 0777 = máximos permisos (solo desarrollo)
    chmod($target_dir, 0777);       // Asegurar permisos
}

// 3. Verificar que sea escribible (opcional pero recomendado)
if (!is_writable($target_dir)) {
    error_log("Error: Carpeta no escribible - " . $target_dir);
    header("Location: index.php?view=newbuyorder&error=folderperms");
    exit;
}
    // Configuración de upload
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
        $total = BuyOrderData::extractTotalFromPDF($target_file);
        
        if($total <= 0) {
            header("Location: index.php?view=newbuyorder&error=nototal");
            exit;
        }
        $order->total = $total;
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