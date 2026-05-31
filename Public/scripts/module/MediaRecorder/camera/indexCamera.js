import { listCameras } from "./listCameras.js";
import { streamTracks, confirmButtons, cameraVideo, devicePreviewStatus, newDeviceId, currentDeviceId, deviceCurrentStatus, cameraOptions, cameraDeviceONOFF } from "../globals.js";
import { endShare } from '../indexMR.js';
const infoUsePicture = document.getElementById('infoUsePicture');
const infoUsePictureNoShowAgain = document.getElementById('infoUsePictureNoShowAgain');
/**
 * Gestiona el stream de cámara y la ventana Picture-in-Picture.
 * Se reinicializa si cambia el dispositivo seleccionado. El PiP se solicita
 * automáticamente en cuanto el vídeo puede reproducirse, para que la cámara
 * permanezca visible mientras el usuario interactúa con el resto de la pantalla.
 * Omite la configuración si la cámara está desactivada o ya está funcionando con el mismo dispositivo.
 */
export async function initializeCamera() {
    if (!deviceCurrentStatus.camera) {
        if (devicePreviewStatus.camera) {
            cameraVideo.removeEventListener('leavepictureinpicture', onLeavePiP);
            confirmButtons.forEach(button => {
                button.removeEventListener('click', onConfirmButtonClick);
            });
            if (document.pictureInPictureElement) {
                await document.exitPictureInPicture();
            }
            if (cameraVideo.srcObject) {
                cameraVideo.srcObject.getTracks().forEach(track => track.stop());
                cameraVideo.srcObject = null;
            }
            infoUsePicture.close();
            streamTracks.camera = null;
        }
        devicePreviewStatus.camera = deviceCurrentStatus.camera;
        return true;
    }
    if (!devicePreviewStatus.camera || currentDeviceId.camera !== newDeviceId.camera) {
        try {
            await listCameras();
            if ((currentDeviceId.camera !== newDeviceId.camera) || (devicePreviewStatus.camera !== deviceCurrentStatus.camera)) {
                streamTracks.camera = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 800 },
                        height: { ideal: 600 },
                        aspectRatio: 4 / 3,
                        deviceId: { exact: newDeviceId.camera }
                    },
                    audio: false
                });
                cameraVideo.srcObject = streamTracks.camera;
                currentDeviceId.camera = newDeviceId.camera;
                cameraVideo.oncanplay = async () => {
                    cameraVideo.play();
                    try {
                        await cameraVideo.requestPictureInPicture();
                    } catch (error) {
                        console.error('Error activando Picture-in-Picture:', error);
                    }
                    cameraVideo.oncanplay = null;
                };
            }
            cameraVideo.addEventListener('leavepictureinpicture', onLeavePiP);
            const cameraPIPInfoAccepted = localStorage.getItem('cameraPIPInfoAccepted');
            if (!cameraPIPInfoAccepted && !document.pictureInPictureElement) {
                infoUsePicture.showModal();
                await waitForConfirmButtonClick(confirmButtons);
            }
            devicePreviewStatus.camera = deviceCurrentStatus.camera;
        } catch (error) {
            console.error(error);
        }
    }
    return;
}

/**
 * Resuelve cuando se hace clic en cualquiera de los botones de confirmación.
 * Pausa la inicialización de la cámara hasta que el usuario cierre el diálogo
 * informativo del PiP.
 *
 * @param {NodeList} buttons
 * @returns {Promise<void>}
 */
function waitForConfirmButtonClick(buttons) {
    return new Promise(resolve => {
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                onConfirmButtonClick();
                resolve();
            });
        });
    });
}

/**
 * Se dispara cuando el usuario cierra la ventana PiP manualmente.
 * Libera el stream y resetea el toggle de cámara al estado desactivado.
 */
function onLeavePiP(){
    endShare(cameraVideo);
    infoUsePicture.close();
    cameraOptions.value ='disabled';
    cameraDeviceONOFF.checked = false;
    devicePreviewStatus.camera = false;
}

function onConfirmButtonClick() {
    try {
        if (infoUsePictureNoShowAgain.checked) {
            localStorage.setItem('cameraPIPInfoAccepted', 'true');
        }
        infoUsePicture.close();
    } catch (error) {
        console.error('Error en requestPictureInPicture:', error);
    }
}