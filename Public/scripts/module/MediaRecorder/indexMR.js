import { cameraDeviceONOFF, cameraOptions, currentDeviceId, defaultDeviceId, deviceCurrentStatus, mediaRecorder, microphoneDeviceONOFF, microphoneOptions, newDeviceId, previewVideo, recordCounting, recordPreview, recordSetting, returnButton, webmDuration, streamTracks } from './globals.js';
import { initializeScreen } from './screen/indexScreen.js';
import { initializeMicrophone } from './microphone/indexMicrophone.js';
import { initializeCamera } from './camera/indexCamera.js';
import { InitializingControllers, formatTime, stopTimer, stopAnalyticsPing, resetTimer } from './controlsRecorder.js';
import { fixWebmDuration } from './fixWebmDuration.js';

/**
 * Arranca la UI del grabador. Conecta los toggles de dispositivo, el flujo
 * de inicio y el diálogo de volver a ajustes. Se llama una sola vez al cargar
 * la página, después de confirmar que getDisplayMedia está disponible.
 */
export function initializeAppMediaRecorder() {
    document.querySelectorAll('.checkbox-body').forEach(function(checkboxBody) {
        checkboxBody.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                const checkbox = document.getElementById(this.parentElement.getAttribute('for'));
                checkbox.checked = !checkbox.checked;
                checkbox.setAttribute('aria-pressed', checkbox.checked);
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    document.getElementById('screen').addEventListener('change', function () {
        document.getElementById('screenCaptureDialog').showModal();
        document.getElementById('screen').checked = true;
    });

    document.getElementById('screenCaptureDialogClose').addEventListener('click', () => {
        document.getElementById('screenCaptureDialog').close();
    });

    document.getElementById('startApp').addEventListener('click', async function () {
        let checkboxes = document.querySelectorAll('.checkbox-device');
        let checkedIds = [];
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                if (checkbox.checked) {
                    checkedIds.push(checkbox.id);
                }
            }
        });
        gtag('event', 'SX_Start', {
            microphone: checkedIds.includes('microphone'),
            camera: checkedIds.includes('camera')
        });
        recordSetting.style.display = 'none';
        recordCounting.style.display = 'block';
        updateDeviceCurrentStatus({
            camera: checkedIds.includes('camera'),
            microphone: checkedIds.includes('microphone')
        });
    });

    const returnDialog = document.getElementById('returnDialog');
    returnDialog.addEventListener('close', () => {
        if (returnDialog.returnValue === 'confirm') {
            goBackToSettings();
        }
    });
    returnButton.addEventListener('click', () => {
        returnDialog.showModal();
    });

    document.getElementById('reRecord').addEventListener('click', async () => {
        const recordedVideo = document.getElementById('recordedVideo');
        if (recordedVideo.src) URL.revokeObjectURL(recordedVideo.src);
        const downloadLink = document.getElementById('downloadLink');
        if (downloadLink.href) URL.revokeObjectURL(downloadLink.href);
        mediaRecorder.videoChunks = [];
        webmDuration.totalTime = 0;
        webmDuration.currentTime = 0;
        resetTimer();
        document.getElementById('recordCapture').style.display = 'flex';
        document.getElementById('pauseCapture').style.display = 'none';
        document.getElementById('resumeCapture').style.display = 'none';
        document.getElementById('stopCapture').style.display = 'none';
        document.getElementById('returnCapure').style.display = 'none';
        await goBackToSettings();
    });

    cameraDeviceONOFF.addEventListener('click', async function () {
        const isChecked = this.checked;
        try {
            if (!isChecked) {
                cameraOptions.value = 'disabled';
                return;
            }
            if (cameraOptions.value == 'enabled' && defaultDeviceId.camera != null) {
                newDeviceId.camera = defaultDeviceId.camera;
                return;
            }
            cameraOptions.value = currentDeviceId.camera;
        } catch (error) {
            console.error(error);
        } finally {
            updateDeviceCurrentStatus({ camera: isChecked });
        }
    });

    microphoneDeviceONOFF.addEventListener('click', async function () {
        const isChecked = this.checked
        try {
            if (!isChecked) {
                microphoneOptions.value = 'disabled';
                return;
            }
            if (microphoneOptions.value == 'enabled' && defaultDeviceId.microphone != null) {
                newDeviceId.microphone = defaultDeviceId.microphone;
                return;
            }
            microphoneOptions.value = currentDeviceId.microphone;
        } catch (error) {
            console.error(error);
        } finally {
            updateDeviceCurrentStatus({ microphone: isChecked });
        }
    });

    cameraOptions.addEventListener('change', async function () {
        const isEnabled = cameraOptions.value !== 'disabled';
        try {
            if (!isEnabled) {
                return;
            }
            if (cameraOptions.value == 'enabled' && defaultDeviceId.camera != null) {
                newDeviceId.camera = defaultDeviceId.camera;
                return;
            }
            newDeviceId.camera = cameraOptions.value;
        } catch (error) {
            console.error(error);
        } finally {
            updateDeviceCurrentStatus({ camera: isEnabled });
        }
    });

    microphoneOptions.addEventListener('change', async function () {
        const isEnabled = microphoneOptions.value !== 'disabled';
        try {
            if (!isEnabled) {
                return;
            }
            if (microphoneOptions.value == 'enabled' && defaultDeviceId.microphone != null) {
                newDeviceId.microphone = defaultDeviceId.microphone;
                return;
            }
            newDeviceId.microphone = microphoneOptions.value;
        } catch (error) {
            console.error(error);
        } finally {
            updateDeviceCurrentStatus({ microphone: isEnabled });
        }
    });
}

/**
 * Sincroniza el estado global de dispositivos y reinicializa todos los streams
 * (pantalla, cámara, micrófono). Es el único punto que gestiona el reensamblado
 * de streams; los toggles y selectores de dispositivo siempre pasan por aquí.
 *
 * @param {{ camera?: boolean, microphone?: boolean, screen?: boolean }} status
 */
export async function updateDeviceCurrentStatus(status) {
    Object.assign(deviceCurrentStatus, status);
    cameraDeviceONOFF.checked = deviceCurrentStatus.camera;
    microphoneDeviceONOFF.checked = deviceCurrentStatus.microphone;
    if (await initializeScreen(goBackToSettings) === false) return;
    await initializeCamera();
    await initializeMicrophone();
    streamTracksUpdate();
}

/**
 * Reconstruye el MediaStream con los tracks activos y crea una nueva instancia
 * de MediaRecorder. Solo se ejecuta si no hay ninguna grabación en curso.
 * El handler onstop corrige los metadatos de duración del WebM antes de
 * ofrecer la descarga, ya que Chrome no los escribe al grabar desde un stream.
 */
function streamTracksUpdate() {
    if (mediaRecorder.recorderStatus) {
        return;
    }

    const stream = new MediaStream();

    if (deviceCurrentStatus.screen) {
        streamTracks.screen.getTracks().forEach(track => stream.addTrack(track));
    }
    if (deviceCurrentStatus.microphone) {
        streamTracks.microphone.getTracks().forEach(track => stream.addTrack(track));
    }
    previewVideo.srcObject = stream;

    mediaRecorder.recorder = new MediaRecorder(stream, { mimeType: 'video/webm;codecs=vp8,opus', audioBitsPerSecond: 128000, videoBitsPerSecond: 6000000 });
    if(!mediaRecorder.controls){
        InitializingControllers();
        mediaRecorder.controls = true;
    }
    
    mediaRecorder.recorder.ondataavailable = function (event) {
        if (event.data.size > 0) {
            mediaRecorder.videoChunks.push(event.data);
        }
    };

    mediaRecorder.recorder.onstop = async () => {
        if (mediaRecorder.videoChunks.length === 0) return;
        const blob = new Blob(mediaRecorder.videoChunks, { type: 'video/webm' });
        const fixedBlob = await fixWebmDuration(blob, webmDuration.totalTime);
        recordedVideo.src = URL.createObjectURL(fixedBlob);

        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        const formattedDate = `${year}-${month}-${day}-${hours}-${minutes}-screenxrecorder`;

        const downloadLink = document.getElementById('downloadLink');
        downloadLink.href = URL.createObjectURL(fixedBlob);
        downloadLink.download = `${formattedDate}.webm`;
        downloadLink.addEventListener('click', () => {
            gtag('event', 'SX_Download', {});
        }, { once: true });

        if (streamTracks.screen) {
            streamTracks.screen.getTracks().forEach(track => track.stop());
            streamTracks.screen = null;
        }
        deviceCurrentStatus.camera = false;
        await initializeCamera();
        deviceCurrentStatus.microphone = false;
        await initializeMicrophone();
    };
}

/**
 * Detiene todos los dispositivos activos (pantalla, cámara, micrófono) y regresa
 * al paso 1 de configuración. Se llama cuando el usuario pulsa "volver" o cuando
 * la compartición de pantalla termina antes de iniciar la grabación.
 */
export async function goBackToSettings() {
    try {
        if (mediaRecorder.recorderStatus) {
            webmDuration.totalTime += Date.now() - webmDuration.currentTime;
            gtag('event', 'SX_StopRecorder', { value: formatTime(webmDuration.totalTime) });
            mediaRecorder.recorderStatus = false;
            mediaRecorder.videoChunks = [];
            microphoneDeviceONOFF.disabled = false;
            microphoneOptions.disabled = false;
            stopTimer();
            resetTimer();
            stopAnalyticsPing();
            if (mediaRecorder.recorder?.state !== 'inactive') {
                mediaRecorder.recorder.stop();
            }
            webmDuration.totalTime = 0;
            webmDuration.currentTime = 0;
        }
        if (streamTracks.screen) {
            streamTracks.screen.getTracks().forEach(track => track.stop());
            streamTracks.screen = null;
        }
        deviceCurrentStatus.screen = false;
        deviceCurrentStatus.camera = false;
        deviceCurrentStatus.microphone = false;
        await initializeCamera();
        await initializeMicrophone();
        previewVideo.srcObject = null;
    } catch (error) {
        console.error(error);
    } finally {
        recordCounting.style.display = 'none';
        recordPreview.style.display = 'none';
        recordSetting.style.display = 'block';
    }
}

/**
 * Para todos los tracks del srcObject de un elemento de vídeo y limpia la referencia.
 * Se usa para liberar correctamente los streams de cámara y captura de pantalla.
 *
 * @param {HTMLVideoElement} video
 */
export function endShare(video) {
    const stream = video.srcObject;
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
}