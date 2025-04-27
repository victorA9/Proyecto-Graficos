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
        <th>Accion</th>
	</thead>
	<?php foreach($orders as $order):?>

        <tr>
            <td style="width:30px;">
                <a href="index.php?view=onebuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-default">
                     <i class="glyphicon glyphicon-eye-open"></i>
                </a>
            </td>
            </td>
            <td>#<?php echo $order->id; ?></td>
            <td>
                <?php if($order->pdf_path != ""): ?>
                <a href="#" class="btn btn-xs btn-info" data-toggle="modal" data-target="#pdfModal" 
                    data-id="<?php echo $order->id; ?>"
                    data-pdf="<?php echo 'http://localhost/BUSINESSLIT/core/storage/orders/' . basename($order->pdf_path); ?>"
                    data-status="<?php echo $order->status; ?>">

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

            <?php if($order->status == 'pendiente' && Core::$user->kind == 1): ?>
                <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#pdfModal"
                    data-id="<?php echo $order->id; ?>"
                    data-pdf="<?php echo 'http://localhost/BUSINESSLIT/core/storage/orders/' . basename($order->pdf_path); ?>"
                    data-status="<?php echo $order->status; ?>">
                    <i class="fa fa-check"></i> Firmar
                 </a>
            <?php endif; ?>
            
            <?php if($order->status == 'pendiente'|| Core::$user->kind == 1): ?>
                <a href="index.php?action=delbuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i>
                </a>
            <?php endif; ?>
            
            </td>
        </tr>

                <!-- Modal para mostrar el PDF -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel">Vista previa del PDF</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfViewer" src="" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                        <div id="modalActions" class="mt-3 text-right">
                            <!-- Aquí se insertará dinámicamente el botón de Firmar -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pdfModal = document.getElementById('pdfModal');
                const pdfViewer = document.getElementById('pdfViewer');
                const modalActions = document.getElementById('modalActions');

                // Escucha el evento de clic en los botones que abren el modal
                document.querySelectorAll('[data-target="#pdfModal"]').forEach(button => {
                    button.addEventListener('click', function () {
                        const pdfPath = button.getAttribute('data-pdf'); // Ruta del PDF
                        const orderId = button.getAttribute('data-id'); // ID de la orden
                        const orderStatus = button.getAttribute('data-status'); // Estado de la orden


                        // Actualiza el iframe con el PDF
                        pdfViewer.src = pdfPath;

                        // Limpia las acciones previas
                        modalActions.innerHTML = '';

                        if (orderStatus === 'pendiente') {
                            // Agrega el botón de "Firmar" dinámicamente
                            const signButton = document.createElement('a');
                            signButton.href = `index.php?action=signbuyorder&id=${orderId}`;
                            signButton.className = 'btn btn-success';
                            signButton.innerHTML = '<i class="fa fa-check"></i> Firmar';
                            modalActions.appendChild(signButton);
                        }
                    });
                });

                // Limpia el iframe al cerrar el modal
                pdfModal.addEventListener('hidden.bs.modal', function () {
                    pdfViewer.src = ''; // Limpia el iframe
                    modalActions.innerHTML = ''; // Limpia las acciones
                });
            });
        </script>
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