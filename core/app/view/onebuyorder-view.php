<?php
            $order = BuyOrderData::getById($_GET['id']);
            if(!$order) header("Location: index.php?view=buyorders");
            ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="./?view=buyorders" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Volver a Órdenes
            </a>
            <h1><i class='fa fa-file-text-o'></i> Orden de Compra #<?php echo $order->id; ?></h1>
            <div class="clearfix"></div>


            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Orden actualizada correctamente
                </div>
            <?php endif; ?>

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Detalles Generales</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="dl-horizontal">
                                <dt>Estado:</dt>
                                <dd>
                                    <span class="label label-<?php echo ($order->status == 'pendiente') ? 'warning' : 'success'; ?>">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </dd>

                                <dt>Total:</dt>
                                <dd><b><?php echo Core::$symbol . " " . number_format($order->total, 2); ?></b></dd>

                                <dt>Creada por:</dt>
                                <dd>
                                    <?php 
                                        $creator = $order->getUser();
                                        echo htmlspecialchars($creator->name); // Muestra el nombre en lugar del ID
                                    ?>
                                </dd>

                                <dt>Fecha creación:</dt>
                                <dd><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></dd>

                                <?php if($order->status == 'firmada' || $order->status == 'procesada'): ?>
                                <dt>Firmada el:</dt>
                                <dd><?php echo date('d/m/Y H:i', strtotime($order->signed_at)); ?></dd>
                                <?php endif; ?>

                                <?php if($order->status == 'firmada'): ?>
                                <dt>Firmada por:</dt>
                                <dd>
                                    <?php 
                                        $signer = UserData::getById($order->signed_by); // Busca al usuario por el campo signed_by
                                        echo htmlspecialchars($signer->name); // Muestra el nombre en lugar del ID
                                    ?>
                                </dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                            <a href="<?php echo 'http://localhost/BUSINESSLIT/core/storage/orders/' . basename($order->pdf_path); ?>" target="_blank" class="btn btn-lg btn-danger">
                                <i class="fa fa-file-pdf-o"></i> Ver PDF Completo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de acciones -->
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="pull-right">
                                <a href="#" onclick="window.print()" class="btn btn-default">
                                    <i class="fa fa-print"></i> Imprimir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial/Comentarios (opcional) -->
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Comentarios</h3>
                </div>
                <div class="box-body">
                    <form action="index.php?action=addcomment" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                        <div class="form-group">
                            <textarea class="form-control" name="comment" placeholder="Agregar comentario..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        .btn, .box-header, .form-group {
            display: none;
        }
        .box {
            border: none;
            box-shadow: none;
        }
    }
</style>