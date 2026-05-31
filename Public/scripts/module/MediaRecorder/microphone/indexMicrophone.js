import { listMicrophones } from "./listMicrophones.js";
import { confirmButtons, currentDeviceId, deviceCurrentStatus, devicePreviewStatus, microphoneDeviceONOFF, microphoneOptions, newDeviceId, streamTracks } from "../globals.js";
const infoUseMicrophone = document.getElementById('infoUseMicrophone');
const infoUseMicrophoneNoShowAgain = document.getElementById('infoUseMicrophoneNoShowAgain');
let streamMicrophone;
/**
 * Gestiona el ciclo de vida del stream de micrófono: solicita el dispositivo,
 * construye el grafo de AudioContext (source → gain → destination) para el stream
 * de grabación, actualiza el VU meter y el visualizador de forma de onda, y aplica
 * la puerta de ruido mediante el slider de umbral.
 * Omite la reinicialización si el dispositivo no ha cambiado desde la última llamada.
 */
export async function initializeMicrophone() {
    if (!deviceCurrentStatus.microphone) {
        if (devicePreviewStatus.microphone) {
            if (streamMicrophone && streamMicrophone.getTracks) {
                streamMicrophone.getTracks().forEach(track => track.stop());
                streamMicrophone = null;
            }
        }
        devicePreviewStatus.microphone = deviceCurrentStatus.microphone;
        return true;
    }
    if (!devicePreviewStatus.microphone || currentDeviceId.microphone != newDeviceId.microphone){
        try {
            await listMicrophones();
            if ((currentDeviceId.microphone !== newDeviceId.microphone) || (devicePreviewStatus.microphone !== deviceCurrentStatus.microphone)) {
                try {
                    streamMicrophone = await navigator.mediaDevices.getUserMedia({ audio: { deviceId: { exact: newDeviceId.microphone } } });
                    currentDeviceId.microphone = newDeviceId.microphone;
                } catch (error) {
                    console.error('Error accessing the microphone:', error);
                    microphoneDeviceONOFF.checked = false;
                    deviceCurrentStatus.microphone = false;
                    microphoneDeviceONOFF.disabled = true;
                    microphoneOptions.disabled = true;
                    document.querySelector('[data-label="microphoneButton"]').classList.add('permission-denied');
                    return;
                }
            }

            let thresholdSliderValue = 50;
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const source = audioContext.createMediaStreamSource(streamMicrophone);
            const analyser = audioContext.createAnalyser();
            const gainNode = audioContext.createGain();
            const destination = audioContext.createMediaStreamDestination();
            gainNode.gain.value = 1;
            source.connect(gainNode);
            source.connect(analyser);
            gainNode.connect(destination);

            streamTracks.microphone = destination.stream;
            
            analyser.fftSize = 256;
            const bufferLength = analyser.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);
            let isGateOpen = true;
            const ATTACK_TIME  = 0.05;
            const RELEASE_TIME = 0.25;
            const HYSTERESIS   = 4;
            function updateVUMeter() {
                analyser.getByteFrequencyData(dataArray);
                const average = dataArray.reduce((acc, value) => acc + value, 0) / bufferLength;
                const percentage = (average / 255) * 100;
                const levelElements = document.querySelectorAll('.level');
                levelElements.forEach((levelElement) => {
                    levelElement.style.clipPath = `inset(${100 - percentage}% 0 0 0)`;
                    levelElement.style.webkitClipPath = `inset(${100 - percentage}% 0 0 0)`;
                });
                if (thresholdSliderValue > 0) {
                    if (isGateOpen && percentage < thresholdSliderValue - HYSTERESIS) {
                        isGateOpen = false;
                        gainNode.gain.cancelScheduledValues(audioContext.currentTime);
                        gainNode.gain.setValueAtTime(gainNode.gain.value, audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + RELEASE_TIME);
                    } else if (!isGateOpen && percentage >= thresholdSliderValue) {
                        isGateOpen = true;
                        gainNode.gain.cancelScheduledValues(audioContext.currentTime);
                        gainNode.gain.setValueAtTime(gainNode.gain.value, audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(1, audioContext.currentTime + ATTACK_TIME);
                    }
                } else if (!isGateOpen) {
                    isGateOpen = true;
                    gainNode.gain.cancelScheduledValues(audioContext.currentTime);
                    gainNode.gain.setValueAtTime(gainNode.gain.value, audioContext.currentTime);
                    gainNode.gain.linearRampToValueAtTime(1, audioContext.currentTime + ATTACK_TIME);
                }
                drawVisualizer(dataArray, percentage);
                requestAnimationFrame(updateVUMeter);
            }

            /**
             * Dibuja el espectro de frecuencias del rango 1–4kHz en el canvas del visualizador.
             * Las barras se muestran en gris cuando la señal está por debajo del umbral de la puerta de ruido.
             *
             * @param {Uint8Array} spectrum  Datos FFT del AnalyserNode
             * @param {number} percentage    Nivel actual como valor 0–100
             */
            function drawVisualizer(spectrum, percentage) {
                const canvas = document.getElementById('visualizerCanvas');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const minFrequency = 1000, maxFrequency = 4000;
                const startIndex = Math.floor(minFrequency / (audioContext.sampleRate / analyser.fftSize));
                const endIndex = Math.ceil(maxFrequency / (audioContext.sampleRate / analyser.fftSize));
                const maxBarHeight = 250, minBarHeight = 1;
                const barWidth = 10;
                const spaceBetweenBars = 10;
                const usableWidth = canvas.width * 0.8; 
                const totalBarWidth = barWidth + spaceBetweenBars;
                const numBars = Math.floor(usableWidth / totalBarWidth); 
                const adjustedEndIndex = Math.min(startIndex + numBars, endIndex);
                const rootStyles = getComputedStyle(document.documentElement);
                const color1 = rootStyles.getPropertyValue('--background-light-1').trim();
                const color2 = rootStyles.getPropertyValue('--background-light-2').trim();
                const greyColor = '#808080';
            
                let startX = (canvas.width - usableWidth) / 2;
                for (let i = 0; i < numBars; i++) {
                    const spectrumIndex = startIndex + Math.floor((adjustedEndIndex - startIndex) * (i / numBars));
                    const barHeight = Math.max(minBarHeight, Math.min(spectrum[spectrumIndex] * (maxBarHeight / 255), maxBarHeight));

                    if (!isGateOpen) {
                        ctx.fillStyle = greyColor;
                    } else {
                        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
                        gradient.addColorStop(0, color1);
                        gradient.addColorStop(1, color2);
                        ctx.fillStyle = gradient;
                    }
            
                    ctx.fillRect(startX, (canvas.height - barHeight) / 2, barWidth, barHeight);
                    startX += totalBarWidth;
                }
            }

            updateVUMeter();

            const controlMute = document.getElementById('controlMute');
            thresholdSliderValue = parseInt(controlMute.value, 10) || 50;
            let previewStatuscontrolMute = thresholdSliderValue;
            controlMute.addEventListener('input', function () {
                thresholdSliderValue = parseInt(this.value, 10);
                document.getElementById('valueSlider').innerText = thresholdSliderValue;
                controlMute.style.setProperty('--range-position', `${thresholdSliderValue}%`);
            });
            controlMute.style.setProperty('--range-position', `${thresholdSliderValue}%`);
            document.querySelector('.muteValue').addEventListener('click', function() {
                const controlMicrophone = document.querySelector('.controlMicrophone');
                controlMicrophone.classList.toggle('disabled');
                if (controlMicrophone.classList.contains('disabled')) {
                    previewStatuscontrolMute = controlMute.value;
                    controlMute.value = 100;
                    controlMute.disabled = true;
                } else {
                    controlMute.value = previewStatuscontrolMute;
                    controlMute.disabled = false;
                }
                controlMute.style.setProperty('--range-position', `${controlMute.value}%`);
                thresholdSliderValue = controlMute.value;
            });
            devicePreviewStatus.microphone = deviceCurrentStatus.microphone;
            document.querySelector('[data-label="microphoneButton"]').classList.remove('permission-denied');
            const microphoneInfoAccepted = localStorage.getItem('microphoneInfoAccepted');
            if (!microphoneInfoAccepted) {
                infoUseMicrophone.showModal();
                await waitForConfirmButtonClick(confirmButtons);
            }
        } catch (error) {
            console.error(error);
            if (error.name === 'NotAllowedError') {
                microphoneDeviceONOFF.checked = false;
                deviceCurrentStatus.microphone = false;
                document.querySelector('[data-label="microphoneButton"]').classList.add('permission-denied');
            }
        }
    }
    return true;
}

/**
 * Resuelve cuando se hace clic en cualquiera de los botones de confirmación.
 * Se usa para pausar la inicialización hasta que el usuario cierre el diálogo
 * informativo del micrófono.
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

function onConfirmButtonClick() {
    try {
        if (infoUseMicrophoneNoShowAgain.checked) {
            localStorage.setItem('microphoneInfoAccepted', 'true');
        }
        infoUseMicrophone.close();
    } catch (error) {
        console.error('Error en requestPictureInPicture:', error);
    }
}