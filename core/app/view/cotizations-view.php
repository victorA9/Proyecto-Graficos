<section class="content"> 
<div class="row">
    <div class="col-md-12">
    <a href="#" id="newCotizationBtn" class="btn btn-default pull-right" data-toggle="modal" data-target="#faceVerificationModal"><i class="fa fa-asterisk"></i> Nueva cotizacion</a>
        <h1><i class='fa fa-square-o'></i> Cotizaciones</h1>
        <div class="clearfix"></div>

<!-- Modal de verificación facial -->
<div class="modal fade" id="faceVerificationModal" tabindex="-1" role="dialog" aria-labelledby="faceVerificationModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="faceVerificationModalLabel">Verificación Facial</h4>
      </div>
      <div class="modal-body">
        <div id="faceVerificationContainer">
          <video id="faceVerificationVideo" width="320" height="240" autoplay muted></video>
          <button id="verifyFaceBtn" class="btn btn-primary" style="margin-top:10px;">
            <i class="fa fa-check"></i> Verificar Rostro
          </button>
          <div id="faceVerificationStatus" class="alert" style="display:none; margin-top:10px;"></div>
          <div id="attemptsCounter" style="margin-top:10px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$products=null;
if(isset($_SESSION["client_id"])){
    $products = SellData::getCotizationsByClientId($_SESSION["client_id"]);
}else if(isset($_SESSION["user_id"])){
        $products = SellData::getCotizations();
}

if(count($products)>0){
    ?>
<br>
<div class="box box-primary">
<div class="box-header">
<h3 class="box-title">Cotizaciones</h3></div>
<table class="table table-bordered table-hover    ">
    <thead>
        <th></th>
        <th>Folio</th>
        <th>Producto</th>
        <th>Pago</th>
        <th>Entrega</th>
        <th>Total</th>
        <th>Fecha</th>
        <th></th>
    </thead>
    <?php foreach($products as $sell):?>

    <tr>
        <td style="width:30px;">
        <a href="index.php?view=onecotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
        <td>#<?php echo $sell->id; ?></td>

        <td>


<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
</td>
<td><?php echo $sell->getP()->name; ?></td>
<td><?php echo $sell->getD()->name; ?></td>
        <td>

<?php
//$total= $sell->total-$sell->discount;
    $total=0;
    foreach($operations as $operation){
        $product  = $operation->getProduct();
        $total += $operation->q*$product->price_out;
    }
        echo "<b>". Core::$symbol." ".number_format($total,2,".",",")."</b>";

?>            

        </td>
        <td><?php echo $sell->created_at; ?></td>
        <td style="width:120px;">
        <?php if(isset($_SESSION["user_id"])):?>
<a href="index.php?view=processcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Procesar</a>
                <?php endif;?>
        <a href="index.php?view=delsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
        </td>
    </tr>

<?php endforeach; ?>

</table>
</div>

<div class="clearfix"></div>

    <?php
}else{
    ?>
    <div class="jumbotron">
        <h2>No hay cotizaciones</h2>
        <p>No se ha realizado ninguna cotizacion.</p>
    </div>
    <?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
    </div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
$(document).ready(function() {
    // Elementos del DOM
    const videoElement = document.getElementById('faceVerificationVideo');
    const statusElement = $('#faceVerificationStatus');
    const verifyBtn = $('#verifyFaceBtn');
    const attemptsElement = $('#attemptsCounter');
    
    // Estado del sistema
    let modelsLoaded = false;
    let stream = null;
    let verificationAttempts = 0;
    let isOnCooldown = false;

    // Función para mostrar estado
    function showStatus(message, type = "info") {
        const icons = {
            info: 'fa-info-circle',
            success: 'fa-check-circle',
            warning: 'fa-exclamation-triangle',
            danger: 'fa-times-circle'
        };
        
        statusElement.html(`<i class="fa ${icons[type]}"></i> ${message}`)
            .removeClass('alert-info alert-success alert-warning alert-danger')
            .addClass(`alert-${type}`)
            .show();
    }

    // Cargar modelos
    async function loadModels() {
        try {
            showStatus("Cargando modelos de reconocimiento...", "info");
            
            await faceapi.nets.tinyFaceDetector.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models');
            
            modelsLoaded = true;
            showStatus("Modelos cargados correctamente", "success");
            return true;
        } catch (error) {
            showStatus("Error al cargar modelos: " + error.message, "danger");
            console.error("Error loading models:", error);
            return false;
        }
    }

    // Iniciar cámara
    async function startCamera() {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: 320,
                    height: 240,
                    facingMode: 'user'
                },
                audio: false
            });
            
            videoElement.srcObject = stream;
            return true;
        } catch (error) {
            let message = "Error al acceder a la cámara: " + error.message;
            showStatus(message, "danger");
            return false;
        }
    }

    // Verificación facial con el servidor
    async function verifyFace() {
        if (isOnCooldown) {
            showStatus("Demasiados intentos. Por favor espere.", "danger");
            return false;
        }

        verificationAttempts++;
        attemptsElement.text(`Intento ${verificationAttempts} de 5`);

        try {
            showStatus("Verificando rostro...", "info");
            
            if (!modelsLoaded) {
                const loaded = await loadModels();
                if (!loaded) throw new Error("No se pudieron cargar los modelos");
            }
            
            // Detectar rostros
            const detections = await faceapi.detectAllFaces(
                videoElement,
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptors();

            if (detections.length === 0) {
                throw new Error("No se detectó un rostro. Por favor mire a la cámara.");
            }

            // Validar descriptor
            const descriptor = detections[0].descriptor;
            if (!descriptor || descriptor.length !== 128) {
                throw new Error("Descriptor facial inválido");
            }

            // Enviar al servidor con credenciales
            const response = await fetch('core/app/action/verifyface.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include', // Importante para enviar cookies de sesión
                body: JSON.stringify({
                    facial_data: Array.from(descriptor),
                    session_check: true
                })
            });

            // Verificar si la respuesta es JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`El servidor respondió con: ${text.substring(0, 100)}...`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || "Error en la verificación");
            }

            if (data.match) {
                showStatus("Verificación exitosa. Redirigiendo...", "success");
                
                setTimeout(() => {
                    $('#faceVerificationModal').modal('hide');
                    window.location.href = "./?view=newcotization";
                }, 1500);
                
                return true;
            } else {
                throw new Error(`Rostro no reconocido (distancia: ${data.distance.toFixed(4)})`);
            }
        } catch (error) {
            console.error("Error en verificación:", error);
            
            if (verificationAttempts >= 5) {
                isOnCooldown = true;
                showStatus("Límite de intentos alcanzado. Intente más tarde.", "danger");
                setTimeout(() => {
                    isOnCooldown = false;
                    verificationAttempts = 0;
                }, 10 * 60 * 1000);
            } else {
                showStatus(error.message, "warning");
            }
            
            return false;
        }
    }

    // Eventos del modal
    $('#faceVerificationModal').on('show.bs.modal', async function() {
        verificationAttempts = 0;
        isOnCooldown = false;
        attemptsElement.text('');
        statusElement.hide();
        
        try {
            await loadModels();
            await startCamera();
            showStatus("Mire a la cámara con buena iluminación", "info");
        } catch (error) {
            showStatus(error.message, "danger");
        }
    });

    $('#faceVerificationModal').on('hidden.bs.modal', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    });

    verifyBtn.click(verifyFace);
});
</script>
