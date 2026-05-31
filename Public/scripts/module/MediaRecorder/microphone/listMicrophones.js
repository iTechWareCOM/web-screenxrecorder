import { defaultDeviceId, newDeviceId } from "../globals.js";
let microphoneListed = false;
const microphoneOptions = document.querySelector('[data-label="microphoneOptions"]');
/**
 * Enumera los dispositivos de entrada de audio y rellena el selector de micrófono.
 * Solo se ejecuta una vez (controlado por microphoneListed). Es necesario llamar a
 * getUserMedia antes de enumerarlos porque la mayoría de navegadores no devuelven
 * las etiquetas de dispositivo hasta que el usuario concede permiso.
 * El delay de 400ms en el finally da tiempo al select para reflejar las nuevas opciones.
 */
export async function listMicrophones() {
    if (!microphoneListed) {
        try {
            const enabledOption = microphoneOptions.querySelector('option[value="enabled"]');
            if (enabledOption) {
                enabledOption.remove();
            }
            await navigator.mediaDevices.getUserMedia({ audio: true });
            const devices = await navigator.mediaDevices.enumerateDevices();
            let defaultFound = false;
            devices.forEach(function (device) {
                if (device.kind === 'audioinput') {
                    const option = new Option(device.label, device.deviceId);
                    microphoneOptions.add(option);
                    if (device.deviceId === 'default') {
                        microphoneOptions.value = newDeviceId.microphone = defaultDeviceId.microphone = device.deviceId;
                        defaultFound = true;
                    }
                }
            });
            if (!defaultFound && devices.length > 0) {
                microphoneOptions.value = newDeviceId.microphone = defaultDeviceId.microphone = devices[0].deviceId;
            }
            if (microphoneOptions.value === '' && devices.length > 0) {
                microphoneOptions.value = newDeviceId.microphone = defaultDeviceId.microphone = devices[0].deviceId;
            }
            microphoneListed = true;
        } catch (error) {
            console.error('Error listing microphones:', error);
            if (error.name === 'NotAllowedError') {
                throw error;
            }
        } finally {
            await new Promise(resolve => setTimeout(resolve, 400));
        }
    }
}

