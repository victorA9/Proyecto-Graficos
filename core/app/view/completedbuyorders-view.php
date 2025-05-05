<!-- filepath: c:\xampp\htdocs\BUSINESSLIT\core\app\view\completedbuyorders-view.php -->
<section class="content"> 
<div class="row">
    <div class="col-md-12">
        <h1><i class='fa fa-check-square-o'></i> Órdenes de Compra Completadas</h1>
        <div class="clearfix"></div>

<?php
$orders = null;
if (isset($_SESSION["user_id"])) {
    $orders = BuyOrderData::getByStatus('completada'); // Obtiene solo las órdenes completadas
}

if (count($orders) > 0) {
?>
<br>
<div class="box box-primary">
<div class="box-header">
<table class="table table-bordered table-hover">
    <thead>
        <th></th>
        <th>Folio</th>
        <th>PDF</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Pago</th>
        <th>Entrega</th>
    </thead>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td style="width:30px;">
                <a href="index.php?view=onebuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-default">
                     <i class="glyphicon glyphicon-eye-open"></i>
                </a>
            </td>
            <td>#<?php echo $order->id; ?></td>
            <td>
                <?php if ($order->pdf_path != ""): ?>
                <a href="#" class="btn btn-xs btn-info" data-toggle="modal" data-target="#pdfModal" 
                    data-id="<?php echo $order->id; ?>"
                    data-pdf="<?php echo 'http://localhost/BUSINESSLIT/core/storage/orders/' . basename($order->pdf_path); ?>"
                    data-status="<?php echo $order->status; ?>">
                    <i class="fa fa-file-pdf-o"></i> Ver PDF
                </a>
                <?php endif; ?>
            </td>
            <td>
                <span class="label label-success">
                    <?php echo ucfirst($order->status); ?>
                </span>
            </td>
            <td>
                <b><?php echo Core::$symbol . " " . number_format($order->total, 2, ".", ","); ?></b>
            </td>
            <td><?php echo ucfirst($order->payment_status); ?></td>
            <td><?php echo ucfirst($order->delivery_status); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
<?php
} else {
?>
<div class="jumbotron">
    <h2>No hay órdenes de compra completadas</h2>
    <p>No se ha registrado ninguna orden completada.</p>
</div>
<?php
}
?>
</div>
</div>
</section>