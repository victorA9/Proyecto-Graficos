<?php

require_once __DIR__ . '/../../../libs/fpdf/fpdf.php';
require_once __DIR__ . '/../../../libs/FPDI-master/src/autoload.php';
require_once __DIR__ . '/../model/BuyOrderData.php';
require_once __DIR__ . '/../../../libs/pdfparser-master/pdfparser-autoload.php';

use Smalot\PdfParser\Parser;
use setasign\Fpdi\Fpdi;

class PDFSigner {
    private static $adminPrivateKey = "noc"; // Cambia esto por tu clave privada

    public static function signPDF($pdfPath, $outputPath, $orderId) {
        // Generar una cadena única basada en el contenido del PDF y la clave privada
        $hash = hash('sha256', file_get_contents($pdfPath) . self::$adminPrivateKey . $orderId);
        
        
        // Crear una instancia de FPDI
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);

        // Importar todas las páginas del PDF original
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
        }

        // Agregar el sello con la cadena encriptada en la última página
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(255, 0, 0); // Color rojo para la firma
        $pdf->SetXY(10, -15); // Posición en la parte inferior izquierda
        $pdf->Write(10, "Firma: $hash");

        // Guardar el PDF firmado
        $pdf->Output($outputPath, 'F');
    }

    public static function validatePDF($pdfPath, $orderId) {
        // Crear una instancia del parser de PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
    
        // Extraer el texto del PDF
        $text = $pdf->getText();
    
        // Usar una expresión regular para extraer la firma
        if (preg_match('/Firma:\s*([a-f0-9]{64})/i', $text, $matches)) {
            $extractedHash = trim($matches[1]); // La firma extraída
            echo "Firma extraída: $extractedHash"; // Depuración
        } else {
            echo "No se encontró la firma en el PDF."; // Depuración
            return false;
        }
    
        // Generar el hash esperado usando el archivo original
        $originalPdfPath = str_replace('signed_', '', $pdfPath); // Ruta del archivo original
        $expectedHash = hash('sha256', file_get_contents($originalPdfPath) . self::$adminPrivateKey . $orderId);
    
        // Validar si el hash extraído coincide con el hash esperado
        return $extractedHash === $expectedHash;
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $orderId = intval($_GET['id']);
    $order = BuyOrderData::getById($orderId);

    if ($order && $order->pdf_path != "") {
        $pdfPath = __DIR__ . "/../../storage/orders/" . basename($order->pdf_path);
        $signedPdfPath = __DIR__ . "/../../storage/orders/signed_" . basename($order->pdf_path);

        // Firmar el PDF
        PDFSigner::signPDF($pdfPath, $signedPdfPath, $orderId);

        // Validar el PDF firmado
        if (PDFSigner::validatePDF($signedPdfPath, $orderId)) {
            // Cambiar el estado de la orden a "firmada"
            $order->signOrder($orderId, $_SESSION['user_id']);
            Core::alert("La orden ha sido validada y firmada exitosamente.");
        } else {
            Core::alert("Error: La firma del PDF no es válida.");
        }

        Core::redir("./?view=buyorders");
    } else {
        Core::alert("Error: No se encontró el PDF de la orden.");
        Core::redir("./?view=buyorders");
    }
} else {
    Core::alert("Error: ID de orden inválido.");
    Core::redir("./?view=buyorders");
}

?>