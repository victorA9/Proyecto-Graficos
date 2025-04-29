<section class="content"> 
<div class="row">
	<div class="col-md-12">
		<h1><i class='fa fa-square-o'></i> Ordenes de compra en proceso</h1>
		<div class="clearfix"></div>


<?php
$orders=null;
if(isset($_SESSION["user_id"])){
    $orders = BuyOrderData::getByStatus('procesada');
}

if(count($orders)>0){

	?>
<br>
<div class="box box-primary">
<div class="box-header">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>PDF</th>
		<th>Estado</th>
		<th>Total</th>
		<th>Pago</th>
		<th>Entrega</th>
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

            <td><?php echo $order->payment_status; ?></td>

            <td><?php echo $order->delivery_status; ?></td>

            <td style="width:150px;">

            

            <?php if($order->payment_status == 'pendiente' || $order->delivery_status == 'pendiente'): ?>
                <a href="index.php?view=processbuyorder&id=<?php echo $order->id; ?>" class="btn btn-xs btn-primary">
            <i class="fa fa-cogs"></i> Procesar
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
    <h2>No hay órdenes de compra en proceso</h2>
    <p>No se ha registrado ninguna orden.</p>
</div>
<?php
}
?>
</div>
</div>






</section>