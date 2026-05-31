import { defaultDeviceId, newDeviceId } from "../globals.js";

let cameraListed = false;
const cameraOptions = document.querySelector('[data-label="cameraOptions"]');

/**
 * Enumera los dispositivos de vídeo y rellena el selector de cámara.
 * Mismo patrón que listMicrophones: getUserMedia primero para desbloquear
 * las etiquetas de dispositivo. Solo se ejecuta una vez por carga de página.
 */
export async function listCameras() {
    if (!cameraListed) {
        try {
            const enabledOption = cameraOptions.querySelector('option[value="enabled"]');
            if (enabledOption) {
                enabledOption.remove();
            }
            await navigator.mediaDevices.getUserMedia({ video: true });
            const devices = await navigator.mediaDevices.enumerateDevices();
            devices.forEach(function (device) {
                if (device.kind === 'videoinput') {
                    const option = new Option(device.label, device.deviceId);
                    cameraOptions.add(option);
                    if (defaultDeviceId.camera == null) {
                        cameraOptions.value = defaultDeviceId.camera = newDeviceId.camera = device.deviceId;
                    }
                }
            });
            cameraListed = true;
        } catch (error) {
            console.error('Error listing devices:', error);
        } finally {
            await new Promise(resolve => setTimeout(resolve, 400));
        }
    }
}