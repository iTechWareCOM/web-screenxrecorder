import { mediaRecorder, microphoneDeviceONOFF, microphoneOptions, webmDuration } from "./globals.js";

let timerTime = 0;
let interval;
let analyticsPingInterval;

export function formatTime(milliseconds) {
    let totalSeconds = Math.floor(milliseconds / 1000);
    let hours = Math.floor(totalSeconds / 3600);
    let minutes = Math.floor((totalSeconds % 3600) / 60);
    let seconds = totalSeconds % 60;
    return [hours, minutes, seconds].map(n => String(n).padStart(2, '0')).join(':');
}

/**
 * Registra los handlers de grabar/pausar/reanudar/detener/volver en los controles
 * de captura. Elimina los listeners anteriores antes de añadir los nuevos para
 * evitar ejecuciones duplicadas cuando el grabador se reinicializa tras cambiar dispositivo.
 */
export function InitializingControllers() {
    const returnCapure = document.getElementById('returnCapure');
    const pauseCapture = document.getElementById('pauseCapture');
    const resumeCapture = document.getElementById('resumeCapture');
    const recordCapture = document.getElementById('recordCapture');
    const stopCapture = document.getElementById('stopCapture');

    recordCapture.removeEventListener('click', handleRecordCapure);
    pauseCapture.removeEventListener('click', handlePauseCapure);
    resumeCapture.removeEventListener('click', handleResumeCapure);
    stopCapture.removeEventListener('click', handleStopCapure);
    returnCapure.removeEventListener('click', handleReturnCapure);

    function handleRecordCapure() {
        gtag('event', 'SX_StartRecorder', {});
        webmDuration.currentTime = Date.now();
        mediaRecorder.recorder.start();
        startTimer();
        startAnalyticsPing();
        recordCapture.style.display = 'none';
        pauseCapture.style.display = stopCapture.style.display = 'flex';
        mediaRecorder.recorderStatus = true;
        microphoneDeviceONOFF.disabled = true;
        microphoneOptions.disabled = true;
    }

    function handlePauseCapure() {
        gtag('event', 'SX_PauseRecorder', {});
        webmDuration.totalTime += Date.now() - webmDuration.currentTime;
        mediaRecorder.recorder.pause();
        stopTimer();
        stopAnalyticsPing();
        pauseCapture.style.display = 'none';
        resumeCapture.style.display = 'flex';
    }

    function handleResumeCapure() {
        gtag('event', 'SX_ResumeRecorder', {});
        webmDuration.currentTime = Date.now();
        mediaRecorder.recorder.resume();
        startTimer();
        startAnalyticsPing();
        resumeCapture.style.display = 'none';
        pauseCapture.style.display = 'flex';
    }

    function handleReturnCapure() {
        gtag('event', 'SX_ReRecord', {});
        returnCapure.style.display = stopCapture.style.display = pauseCapture.style.display = resumeCapture.style.display = 'none';
        recordCapture.style.display = 'flex';
        mediaRecorder.videoChunks = [];
        stopTimer();
        resetTimer();
        stopAnalyticsPing();
    }

    recordCapture.addEventListener('click', handleRecordCapure);
    pauseCapture.addEventListener('click', handlePauseCapure);
    resumeCapture.addEventListener('click', handleResumeCapure);
    stopCapture.addEventListener('click', handleStopCapure);
    returnCapure.addEventListener('click', handleReturnCapure);
}

/**
 * Para la grabación, calcula la duración total acumulada y cambia la UI
 * al panel de previsualización. Está exportada para que indexScreen.js pueda
 * llamarla cuando el usuario termina la compartición de pantalla a mitad de grabación.
 */
export function handleStopCapure() {
    webmDuration.totalTime += Date.now() - webmDuration.currentTime;
    gtag('event', 'SX_StopRecorder', {
        value: formatTime(webmDuration.totalTime)
    });
    mediaRecorder.recorder.stop();
    mediaRecorder.recorderStatus = false;
    recordCounting.style.display = 'none';
    recordPreview.style.display = 'flex';
    microphoneDeviceONOFF.disabled = false;
    microphoneOptions.disabled = false;
    stopTimer();
    stopAnalyticsPing();
}

export const startAnalyticsPing = () => {
    analyticsPingInterval = setInterval(() => {
        gtag('event', 'SX_RecordingActive', { interval: 'ping' });
    }, 30000);
};

export const stopAnalyticsPing = () => {
    clearInterval(analyticsPingInterval);
};

const startTimer = () => {
    interval = setInterval(incrementTimer, 1000);
    const timerGo = document.querySelector('[data-label="timerContainer"]');
    timerGo.classList.add('go');
};

export const stopTimer = () => {
    clearInterval(interval);
    const timerGo = document.querySelector('[data-label="timerContainer"]');
    timerGo.classList.remove('go');
};

export const resetTimer = () => {
    const timer = document.querySelector('[data-label="timer"]');
    timer.textContent = "00:00";
    timerTime = 0;
};

/**
 * Añade un cero a la izquierda si el número es de un solo dígito.
 *
 * @param {number} number
 * @returns {string}
 */
const pad = (number) => {
    return (number < 10) ? '0' + number : number;
};

const incrementTimer = () => {
    const timer = document.querySelector('[data-label="timer"]');
    timerTime++;
    const numberMinutes = Math.floor(timerTime / 60);
    const numberSeconds = timerTime % 60;
    timer.textContent = pad(numberMinutes) + ":" + pad(numberSeconds);
};
