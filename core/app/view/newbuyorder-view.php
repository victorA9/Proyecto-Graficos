<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="./?view=buyorders" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Regresar
            </a>
            <h1><i class='fa fa-file-text-o'></i> Nueva Orden de Compra</h1>
            <div class="clearfix"></div>
            
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Complete los datos</h3>
                </div>
                
				<form action="index.php?action=savebuyorder" method="post" enctype="multipart/form-data">
				<div class="box-body">
                        <!-- Campo para subir PDF -->
                        <div class="form-group">
                            <label for="pdf_file">Cotización PDF:</label>
                            <input type="file" class="form-control" name="pdf_file" id="pdf_file" accept=".pdf" required>
                            <p class="help-block">Suba el archivo PDF con la cotización del proveedor</p>
                        </div>
                </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-floppy-o"></i> Guardar Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Script para vista previa del PDF -->
<script>
document.getElementById('pdf_file').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if(file.type !== 'application/pdf') {
        alert('Solo se permiten archivos PDF');
        e.target.value = '';
    }
});
</script>