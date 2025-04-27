<?php
// filepath: c:\xampp\htdocs\BUSINESSLIT\core\app\action\signbuyorder-action.php

// Asegúrate de que el ID de la orden esté presente en la solicitud
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $orderId = intval($_GET['id']); // Sanitiza el ID
    $userId = $_SESSION['user_id']; // Obtén el ID del usuario actual

    // Obtén la orden de compra por su ID
    $order = BuyOrderData::getById($orderId);

    if ($order) {
        // Verifica si la orden está en estado "pendiente"
        if ($order->status === BuyOrderData::STATUS_PENDING) {
            // Marca la orden como "firmada"
            $order->signOrder($orderId, $userId);

            // Redirige con un mensaje de éxito
            print "<script>alert('La orden ha sido firmada exitosamente.');</script>";
            print "<script>window.location='index.php?view=buyorders';</script>";
        } else {
            // Si la orden no está pendiente, muestra un mensaje de error
            print "<script>alert('La orden no está en estado pendiente.');</script>";
            print "<script>window.location='index.php?view=buyorders';</script>";
        }
    } else {
        // Si no se encuentra la orden, muestra un mensaje de error
        print "<script>alert('Orden no encontrada.');</script>";
        print "<script>window.location='index.php?view=buyorders';</script>";
    }
} else {
    // Si no se proporciona un ID válido, redirige con un mensaje de error
    print "<script>alert('ID de orden inválido.');</script>";
    print "<script>window.location='index.php?view=buyorders';</script>";
}