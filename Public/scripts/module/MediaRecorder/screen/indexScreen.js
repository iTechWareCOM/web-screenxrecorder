import { handleStopCapure } from '../controlsRecorder.js';
import { streamTracks, modalBackground, deviceCurrentStatus, mediaRecorder } from '../globals.js';

/**
 * Solicita al usuario que elija una pantalla o ventana para compartir mediante getDisplayMedia.
 * Muestra un overlay modal mientras el selector del navegador está abierto y lo oculta en el
 * finally para que desaparezca tanto si el usuario elige una fuente como si cancela.
 *
 * Si la compartición termina mientras hay una grabación activa (el usuario pulsa "Dejar de compartir"
 * en la barra del navegador), dispara una parada completa de la grabación. Si aún no había
 * comenzado, invoca onEndedBeforeRecord para cancelar y volver al paso 1.
 *
 * @param {Function} onEndedBeforeRecord  Callback a llamar si la pantalla se detiene antes de grabar
 */
export async function initializeScreen(onEndedBeforeRecord) {
    if(!deviceCurrentStatus.screen){
        try {
            const infoScreen = document.getElementById("infoScreen");
            infoScreen.style.display = 'block';
            modalBackground.style.display = 'block';
            streamTracks.screen = await navigator.mediaDevices.getDisplayMedia({ video: { mediaSource: "screen", displaySurface: "monitor" }, audio: true });
            streamTracks.screen.getVideoTracks()[0].addEventListener('ended', () => {
                if(mediaRecorder.recorderStatus){
                    handleStopCapure();
                    return;
                }
                onEndedBeforeRecord?.();
            });
            deviceCurrentStatus.screen = true;
        } catch (error) {
            console.error(error);
            await onEndedBeforeRecord?.();
            return false;
        } finally {
            infoScreen.style.display = 'none';
            modalBackground.style.display = 'none';
        }
    }
    return;
}