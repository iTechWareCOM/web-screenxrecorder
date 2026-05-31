export const APP_RM = {status:true}
export const deviceCurrentStatus = {screen:false,camera:false,microphone:false};
export const devicePreviewStatus = {camera:false,microphone:false};

export const defaultDeviceId = {camera:null,microphone:null};
export const currentDeviceId = {camera:null,microphone:null};
export const newDeviceId = {camera:null,microphone:null};

export const streamTracks = {screen:null,camera:null,microphone:null};
export const mediaRecorder = {recorder:null,videoChunks:[],recorderStatus:false,controls:false};

export const modalBackground = document.querySelector('[data-label="modalBackground"]');
export const confirmButtons = document.querySelectorAll('.buttonConfirm');

export const cameraDeviceONOFF = document.getElementById('cameraDeviceONOFF');
export const cameraOptions = document.querySelector('[data-label="cameraOptions"]');
export const cameraVideo = document.getElementById('cameraPIP');
export const microphoneDeviceONOFF = document.getElementById('microphoneDeviceONOFF');
export const microphoneOptions = document.querySelector('[data-label="microphoneOptions"]');

export const recordSetting = document.getElementById("recordSetting");
export const recordCounting = document.getElementById("recordCounting");
export const recordPreview = document.getElementById("recordPreview");

export const previewVideo = document.getElementById("previewVideo");
export const webmDuration = {currentTime:0,totalTime:0};
export const returnButton = document.getElementById('return');