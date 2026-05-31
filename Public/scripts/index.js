import initializeLights from './module/lightsBackground.js';

const q = new URL(import.meta.url).search;

const { initializeAppMediaRecorder } = await import(`./module/MediaRecorder/indexMR.js${q}`);

initializeLights();
if (!navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
    document.querySelector('.recordSetting .row_0').style.display = 'block';
    document.querySelector('.recordSetting .row_1').style.display = 'none';
} else {
    initializeAppMediaRecorder();
}
