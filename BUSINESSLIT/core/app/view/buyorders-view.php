<section class="content"> 
<div class="row">
	<div class="col-md-12">
	<a href="./?view=newbuyorder" class="btn btn-default pull-right"><i class="fa fa-asterisk"></i> Nueva orden de compra</a>
		<h1><i class='fa fa-square-o'></i> Ordenes de compra</h1>
		<div class="clearfix"></div>


<?php
$orders=null;
if(isset($_SESSION["client_id"])){
	$products = SellData::getCotizationsByClientId($_SESSION["client_id"]);
}else if(isset($_SESSION["user_id"])){
		$orders = BuyOrderData::getAll();

}

if(count($orders)>0){

	?>
<br>
<div class="box box-primary">
<div class="box-header">
<h3 class="box-title">Ordenes de compra</h3></div>
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>PDF</th>
		<th>Estado</th>
		<th>Total</th>
		<th>Creada por</th>
		<th>Fecha</th>
        <th>Firma</th>
		<th></th>
	</thead>
	<?php foreach($orders as $order):?>

        <tr>
            <td style="width:30px;">
                <a href="index.php?view=onebuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-default">
                     <i class="glyphicon glyphicon-eye-open"></i>
                </a>
            </td>
            <td>#<?php echo $order->id; ?></td>
            <td>
                <?php if(!empty($order->pdf_path)): ?>
                    <a href="<?php echo $order->pdf_path; ?>" target="_blank" class="btn btn-xs btn-info">
                        <i class="fa fa-file-pdf-o"></i> Ver PDF
                    </a>
                <?php endif; ?>
            </td>
            <td>
                <span class="label label-<?php echo ($order->status == 'pendiente') ? 'warning' : 'success'; ?>">
                    <?php echo ucfirst($order->status); ?>
                </span>
            </td>
            <td>
                <b><?php echo Core::$symbol . " " . number_format($order->total, 2, ".", ","); ?></b>
            </td>
            <td>
                <?php echo $order->getUser()->name; // Asume método getUser() ?>
            </td>
            <td><?php echo $order->created_at; ?></td>
            <td style="width:150px;">
            <?php if($order->status == 'pendiente' && esAdmin()): ?>
                <a href="index.php?action=signbuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-success">
                    <i class="fa fa-check"></i> Firmar
                 </a>
            <?php endif; ?>
                <a href="index.php?action=delbuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
<?php
} else {
?>
<div class="jumbotron">
    <h2>No hay órdenes de compra</h2>
    <p>No se ha registrado ninguna orden.</p>
</div>
<?php
}
?>
</div>
</div>
</section>