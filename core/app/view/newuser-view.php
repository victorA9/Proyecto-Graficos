<section class="content">
<div class="row">
    <div class="col-md-12">
    <h1>Agregar Usuario</h1>
    <br>
    <form class="form-horizontal" id="userForm" enctype="multipart/form-data" method="post" 
    action="core/app/action/adduser-action.php" role="form">

        <!-- Sección de Reconocimiento Facial -->
        <div class="form-group">
            <label class="col-lg-2 control-label">Registro Facial</label>
            <div class="col-md-6">
                <button type="button" id="startFaceCapture" class="btn btn-info">
                    <i class="fa fa-camera"></i> Activar Cámara
                </button>
                <div id="faceCaptureContainer" style="display:none; margin-top:15px;">
                    <video id="faceVideo" width="320" height="240" autoplay muted></video>
                    <button type="button" id="captureFaceBtn" class="btn btn-primary" style="margin-top:10px;">
                        <i class="fa fa-check"></i> Capturar Rostro
                    </button>
                    <div id="faceStatus" class="alert" style="display:none; margin-top:10px;"></div>
                </div>
                <input type="hidden" name="facial_data" id="facialDataInput">
                <p class="help-block">Permita acceso a la cámara y asegure buena iluminación.</p>
            </div>
        </div>

        <!-- Campos del formulario -->
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Imagen (160x160)</label>
            <div class="col-md-6">
                <input type="file" name="image" id="image" placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
            <div class="col-md-6">
                <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
            <div class="col-md-6">
                <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Apellido" required>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre de usuario*</label>
            <div class="col-md-6">
                <input type="text" name="username" class="form-control" required id="username" placeholder="Nombre de usuario">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
            <div class="col-md-6">
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            </div>
        </div>
        
        <?php if(isset($_GET["kind"]) && $_GET["kind"]=="3") { ?>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Comisión de ventas(%)</label>
            <div class="col-md-6">
                <input type="number" name="comision" class="form-control" id="inputEmail1" placeholder="Comisión de ventas(%)" step="0.01">
            </div>
        </div>
        <?php } ?>

        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Contraseña*</label>
            <div class="col-md-6">
                <input type="password" name="password" class="form-control" required id="inputEmail1" placeholder="Contraseña">
            </div>
        </div>

        <?php if(isset($_GET["kind"]) && ($_GET["kind"]=="2" || $_GET["kind"]=="3")) { ?>
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Almacén</label>
            <div class="col-md-6">
            <?php 
            $clients = StockData::getAll();
            ?>
            <select name="stock_id" class="form-control" required>
                <option value="">-- NINGUNO --</option>
                <?php foreach($clients as $client) { ?>
                <option value="<?php echo $client->id; ?>"><?php echo $client->name; ?></option>
                <?php } ?>
            </select>
            </div>
        </div>
        <?php } ?>
        
        <input type="hidden" name="kind" value="<?php echo $_GET['kind']; ?>">
        <p class="alert alert-info">* Campos obligatorios</p>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
            </div>
        </div>
    </form>
    </div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
$(document).ready(function() {
    // Configuración avanzada
    const FACE_REGISTRATION_CONFIG = {
        samplesRequired: 3,
        minConfidence: 0.7,
        modelSources: [
            'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/models',
            '/models',
            'https://justadudewhohacks.github.io/face-api.js/models'
        ],
        requiredModels: [
            'tinyFaceDetector',
            'faceLandmark68Net',
            'faceRecognitionNet'
        ]
    };

    const video = document.getElementById('faceVideo');
    const facialInput = $('#facialDataInput');
    const statusDiv = $('#faceStatus');
    const faceContainer = $('#faceCaptureContainer');
    const startBtn = $('#startFaceCapture');
    const captureBtn = $('#captureFaceBtn');
    const samplesCounter = $('#samplesCounter');
    
    let modelsLoaded = false;
    let stream = null;
    let samples = [];
    
    // Función para mostrar estado
    function showStatus(message, type = "info", timeout = 0) {
        const icons = {
            info: 'info-circle',
            success: 'check-circle',
            warning: 'exclamation-triangle',
            danger: 'times-circle'
        };
        
        statusDiv.html(`<i class="fa fa-${icons[type] || icons.info}"></i> ${message}`)
            .removeClass().addClass(`alert alert-${type}`)
            .show();
        
        if (timeout > 0) {
            setTimeout(() => statusDiv.fadeOut(), timeout);
        }
    }
    
    // Cargar modelos
    async function loadModels() {
        let lastError = null;
        
        for (const source of FACE_REGISTRATION_CONFIG.modelSources) {
            try {
                showStatus(`Cargando modelos desde ${source}...`, 'info');
                
                await Promise.all(
                    FACE_REGISTRATION_CONFIG.requiredModels.map(model => 
                        faceapi.nets[model].loadFromUri(source)
                    )
                );
                
                modelsLoaded = true;
                showStatus('Modelos cargados correctamente', 'success', 2000);
                return true;
            } catch (error) {
                lastError = error;
                console.error(`Error cargando modelos desde ${source}:`, error);
            }
        }
        
        throw lastError || new Error('No se pudieron cargar los modelos');
    }
    
    // Iniciar cámara
    async function startCamera() {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: 640,
                    height: 480,
                    facingMode: 'user'
                }
            });
            
            video.srcObject = stream;
            return true;
        } catch (error) {
            let message = 'Error al acceder a la cámara';
            if (error.name === 'NotAllowedError') {
                message = 'Permiso de cámara denegado. Por favor habilite el acceso.';
            } else if (error.name === 'NotFoundError') {
                message = 'No se encontró dispositivo de cámara.';
            }
            
            throw new Error(message);
        }
    }
    
    // Capturar muestra
    async function captureSample() {
        if (!modelsLoaded) {
            await loadModels();
        }
        
        showStatus('Analizando rostro...', 'info');
        
        const detections = await faceapi.detectAllFaces(video, 
            new faceapi.TinyFaceDetectorOptions({
                inputSize: 512,
                scoreThreshold: FACE_REGISTRATION_CONFIG.minConfidence
            }))
            .withFaceLandmarks()
            .withFaceDescriptors();
        
        if (detections.length === 0) {
            throw new Error('No se detectó un rostro. Asegúrese de:');
        }
        
        if (detections.length > 1) {
            throw new Error('Se detectó más de un rostro. Asegúrese de estar solo frente a la cámara.');
        }
        
        const detection = detections[0];
        if (detection.detection.score < FACE_REGISTRATION_CONFIG.minConfidence) {
            throw new Error(`Confianza de detección baja (${Math.round(detection.detection.score * 100)}%). Mejore la iluminación.`);
        }
        
        // Normalizar descriptor
        const descriptor = detection.descriptor;
        const magnitude = Math.sqrt(descriptor.reduce((sum, val) => sum + val * val, 0));
        const normalizedDescriptor = magnitude > 0 
            ? descriptor.map(val => val / magnitude) 
            : descriptor;
        
        return normalizedDescriptor;
    }
    
    // Iniciar captura
    startBtn.click(async function() {
        try {
            $(this).prop('disabled', true);
            samples = [];
            samplesCounter.text('');
            
            await loadModels();
            await startCamera();
            
            faceContainer.show();
            captureBtn.prop('disabled', false);
            
            showStatus('Mire directamente a la cámara con buena iluminación', 'info');
            
            // Crear indicador visual
            if (!$('#faceIndicator').length) {
                $(video).after(`
                    <div id="faceIndicator" style="
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: #28a745;
                        color: white;
                        padding: 5px 10px;
                        border-radius: 5px;
                        display: none;
                        z-index: 1000;
                        font-size: 12px;
                    ">
                        <i class="fa fa-user"></i> Rostro detectado
                    </div>
                `);
            }
        } catch (error) {
            showStatus(error.message, 'danger');
            $(this).prop('disabled', false);
        }
    });
    
    // Capturar rostro
    captureBtn.click(async function() {
        try {
            $(this).prop('disabled', true);
            
            const sample = await captureSample();
            samples.push(sample);
            
            samplesCounter.text(`Muestras capturadas: ${samples.length}/${FACE_REGISTRATION_CONFIG.samplesRequired}`);
            showStatus(`Muestra ${samples.length}/${FACE_REGISTRATION_CONFIG.samplesRequired} capturada (${Math.round(sample[0] * 100)}% confianza)`, 'success');
            
            if (samples.length >= FACE_REGISTRATION_CONFIG.samplesRequired) {
                // Calcular descriptor promedio
                const avgDescriptor = [];
                for (let i = 0; i < samples[0].length; i++) {
                    let sum = 0;
                    for (let j = 0; j < samples.length; j++) {
                        sum += samples[j][i];
                    }
                    avgDescriptor.push(sum / samples.length);
                }
                
                // Normalizar el descriptor promedio
                const magnitude = Math.sqrt(avgDescriptor.reduce((sum, val) => sum + val * val, 0));
                const finalDescriptor = magnitude > 0 
                    ? avgDescriptor.map(val => val / magnitude) 
                    : avgDescriptor;
                
                facialInput.val(JSON.stringify(finalDescriptor));
                showStatus('Registro facial completado exitosamente!', 'success', 5000);
                
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                
                faceContainer.hide();
            } else {
                showStatus(`Mueva ligeramente la cabeza para la muestra ${samples.length + 1}`, 'info');
                $(this).prop('disabled', false);
            }
        } catch (error) {
            showStatus(error.message, 'warning');
            $(this).prop('disabled', false);
        }
    });
    
    // Limpiar al cerrar
    $(window).on('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
