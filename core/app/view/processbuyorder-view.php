
<section class="content">

<h1>Procesar Orden de Compra</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
  <?php
  $order = BuyOrderData::getById($_GET["id"]);
  $total = $order->total;
  ?>

<form method="post" class="form-horizontal" id="processbuyorder" action="index.php?action=processbuyorder">
    <input type="hidden" name="order_id" value="<?php echo $_GET["id"]; ?>">

    <div class="row">
        <div class="col-md-3">
            <label class="control-label">Descuento</label>
            <div class="col-lg-12">
                <input type="text" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
            </div>
        </div>

        <div class="col-md-3">
            <label class="control-label">Efectivo</label>
            <div class="col-lg-12">
                <input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo" 
                value="<?php echo $order->payment_status == 'pagado' ? $order->total : ''; ?>" 
                       <?php echo $order->payment_status == 'pagado' ? 'readonly' : ''; ?>>
            
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label class="control-label">Entrega</label>
            <div class="col-lg-12">
              <select name="delivery_status" class="form-control">
                <option value="pendiente" <?php echo $order->delivery_status == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="entregado" <?php echo $order->delivery_status == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
              </select>
            </div>
        </div>

        <div class="col-md-4">
            <label class="control-label">Pago</label>
            <div class="col-lg-12">
              <select name="payment_status" class="form-control" <?php echo $order->payment_status == 'pagado' ? 'disabled' : ''; ?>>
                <option value="pendiente" <?php echo $order->payment_status == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="pagado" <?php echo $order->payment_status == 'pagado' ? 'selected' : ''; ?>>Pagado</option>
             </select>
            </div>
        </div>

        <div class="col-md-4">
            <label class="control-label">&nbsp;</label>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="glyphicon glyphicon-usd"></i> Procesar
                </button>
            </div>
        </div>
    </div>
</form>
<br>

<div class="box box-primary">
    <br>
    <table class="table table-bordered table-hover">
        <thead>
            <th>Codigo</th>
            <th>Firma</th>
            <th>Total</th>
        </thead>
	
<tr>
	<td><?php echo $order->id ;?></td>
	<td><?php echo $order->signed_at; ;?></td>
	<td><?php echo $order->total ;?></td>
</tr>
    </table>
</div>
<br><br>
<h1>Total: $ <?php echo number_format($total, 2, '.', ','); ?></h1>

<?php endif; ?>
</section>

<script>
    $("#processbuyorder").submit(function(e){
        discount = $("#discount").val();
        money = $("#money").val();
        paymentStatus = $("select[name='payment_status']").val(); // Obtén el valor del select de estado de pago

        if(paymentStatus === "pagado" && money < (<?php echo $total; ?> - discount)){
            alert("Efectivo insuficiente!");
        }else if(paymentStatus === "pendiente" && money < (<?php echo $total; ?> - discount)){
            alert("No olvide actualizar el estado de pago después");
        } else {
            if(discount == "") { discount = 0; }
            go = confirm("Cambio: $" + (money - (<?php echo $total; ?> - discount)));
            if(!go) { e.preventDefault(); }
        }
    });
</script>