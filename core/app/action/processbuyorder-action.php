<?php
if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']); // Sanitiza el ID
    $order = BuyOrderData::getById($orderId);

    if ($order && $order->status === BuyOrderData::STATUS_SIGNED || $order->status === BuyOrderData::STATUS_PROCESSED) {
        // Actualiza el estado de la orden a "en proceso"
        $order->processOrder($orderId);

        // Actualiza los estados de entrega y pago si están presentes
        if (isset($_POST['delivery_status'])) {
            $order->updateDeliveryStatus($orderId, $_POST['delivery_status']);
        }

        if ( isset($_POST['payment_status'])) {
            $order->updatePaymentStatus($orderId, $_POST['payment_status']);
        }

        $updatedOrder = BuyOrderData::getById($orderId);
        // Actualiza la información de la orden
        if($updatedOrder->payment_status === BuyOrderData::PAYMENT_PAID && $updatedOrder->delivery_status === BuyOrderData::DELIVERY_DELIVERED) {
            $updatedOrder->completeOrder($orderId);
            Core::alert("Orden Completada.");
            Core::redir("./?view=processedbuyorders");
        }

        Core::alert("Orden procesada exitosamente.");
        Core::redir("./?view=processedbuyorders");
    } else {
        Core::alert("Error: La orden no está firmada o no existe.");
        Core::redir("./?view=processedbuyorders");
    }
} else {
    Core::alert("Error: ID de orden inválido.");
    Core::redir("./?view=processedbuyorders");
}
?>