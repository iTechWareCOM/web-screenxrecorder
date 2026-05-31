
<div id="recordSetting" class="section section_0 recordSetting" >
    <h1><?=$translation['title_2']?></h1>
    <div class="row_0" style="display:none;">
        <svg id="notAvailable" viewBox="0 0 500 281.04493" xmlns="http://www.w3.org/2000/svg"><use href="#icon-notAvailable"></use></svg>
        <span style="font-size:1.5rem;font-weight:500;text-aling:center;"><?=$translation['notAvailable']?></span><br><span style="font-weight:300;"><?=$translation['notAvailable_description']?></span>
    </div>
    <div class="row_1">
        <p><?=$translation['initialConfig']?></p>
        <div class="preselector margin_top_3">
            <div class="row_0">
                <div class="item">
                    <div class="checkbox">
                        <label for="screen" class="checkbox-wrapper">
                            <input hidden id="screen" type="checkbox" name="checkbox-device" class="checkbox-device" checked aria-labelledby="screen-label" />
                            <span class="checkbox-body" tabindex="0" role="button">
                                <span class="checkbox-icon">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-screen"></use></svg>
                                </span>
                                <span id="screen-label" class="checkbox-label">
                                    <?=$translation['screen']?>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>                 
                <div class="item">
                    <div class="checkbox">
                        <label for="microphone" class="checkbox-wrapper">
                            <input hidden id="microphone" type="checkbox" class="checkbox-device" checked aria-labelledby="microphone-label" />
                            <span class="checkbox-body" tabindex="0" role="button">
                                <span class="checkbox-icon">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-microphone"></use></svg>
                                </span>
                                <span id="microphone-label" class="checkbox-label">
                                    <?=$translation['microphone']?>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="item">
                    <div class="checkbox">
                        <label for="camera" class="checkbox-wrapper">
                            <input hidden id="camera" type="checkbox" name="checkbox-device" class="checkbox-device deviceONOFFInitial" aria-labelledby="camera-label" />
                            <span class="checkbox-body" tabindex="0" role="button">
                                <span class="checkbox-icon">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-camera"></use></svg>
                                </span>
                                <span id="camera-label" class="checkbox-label">
                                    <?=$translation['camera']?>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row_1">
                <button id="startApp" class="buttonConfirm"><?=$translation['nextStep']?></button>
            </div>
        </div>
        <p class="compatibility"><?=$translation['compatibility']?></p>
    </div>
</div>
<div id="recordCounting" class="section section_1 recordCounting" style="display:none;">
    <div class="headerRecord">
        <div class="return">
            <button id="return" class="returnButton">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-returnButton"></use></svg>
            </button>
        </div>
        <div class="timer" data-label="timerContainer">
            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-timerContainer"></use></svg>
            <span data-label="timer">00:00</span>
        </div>
        <div class="controlRecord">
            <button id="returnCapure" class="controlRecordButton return" style="display:none;">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-returnCapure"></use></svg>
                <?=$translation['returnCapure']?>
            </button>
            <button id="pauseCapture" class="controlRecordButton pause" style="display:none;">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-pauseCapture"></use></svg>
                <?=$translation['pauseCapture']?>
            </button>
            <button id="resumeCapture" class="controlRecordButton resume" style="display:none;">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-resumeCapture"></use></svg>
                <?=$translation['resumeCapture']?>
            </button>
            <button id="recordCapture" class="controlRecordButton record">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-recordCapture"></use></svg>
                <?=$translation['recordCapture']?>
            </button>
            <button id="stopCapture" class="controlRecordButton stop" style="display:none;">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><use href="#icon-stopCapture"></use></svg>
                <?=$translation['stopCapture']?>
            </button>
        </div>
    </div>
    <div id="previewCapture" class="previewCapture row_0">
        <video class="video" id="previewVideo" width="1280" height="720" autoplay="" muted=""></video>
        <div class="cover video"></div>
        <canvas class="sound" id="visualizerCanvas" width="1280" height="720"></canvas>
    </div>
    <div class="controls">
        <div class="boxButton">
            <div data-label="cameraButton" class="camera inputButton">
                <label for="cameraDeviceONOFF" class="checkbox-wrapper">
                    <input hidden id="cameraDeviceONOFF" type="checkbox" name="checkbox-device" class="checkbox-device" />
                    <span class="checkbox-body">
                        <span class="checkbox-icon">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-camera"></use></svg>
                        </span>
                        <span class="checkbox-label">
                            <?=$translation['camera']?>
                        </span>
                    </span>
                </label>
                <select data-label="cameraOptions">
                    <option value="disabled">
                        <?=$translation['disabled']?>
                    </option>
                    <option value="enabled">
                        <?=$translation['enabled']?>
                    </option>
                </select>
            </div>
        </div>
        <div class="boxButton">
            <div data-label="microphoneButton" class="microphone inputButton">
                <label for="microphoneDeviceONOFF" class="checkbox-wrapper">
                    <input hidden id="microphoneDeviceONOFF" type="checkbox" class="checkbox-device"/>
                    <span class="checkbox-body">
                        <span class="checkbox-icon">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-microphone"></use></svg>
                        </span>
                        <span class="checkbox-label">
                            <?=$translation['microphone']?>
                        </span>
                    </span>
                </label>
                <select data-label="microphoneOptions">
                    <option value="disabled">
                        <?=$translation['disabled']?>
                    </option>
                    <option value="enabled">
                        <?=$translation['enabled']?>
                    </option>
                </select>
            </div>
            <div class="controlMicrophone">
                <div class="levels" id="levels">
                    <input type="range" orient="vertical" class="slider" id="controlMute" min="0" max="100" value="5" style="--range-position: 10%;">
                    <div class="level"></div>
                </div>
                <div class="muteValue" role="button">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><use href="#icon-microphone"></use></svg>
                    <span id="valueSlider">5</span>
                </div>
            </div>
        </div>
    </div>
    <video style="display: none;" id="cameraPIP" width="200" height="200" autoplay=""></video>
</div>
<div id="recordPreview" class="section section_2 recordPreview" style="display:none;">
    <h2><?=$translation['endRecorder']?></h2>
    <div id="videoCapture" class="videoCapture">
        <video id="recordedVideo" width="600" height="400" controls=""></video>
    </div>
    <div class="recordPreviewOptions">
        <button id="reRecord" class="buttonConfirm"><?=$translation['reRecord']?></button>
        <a id="downloadLink" href="" class="buttonConfirm"><?=$translation['download']?></a>
    </div>
</div>
<section class="section section_3">
    <div class="row_0">
        <div class="column_0">
            <h2><?=$translation['title_3']?></h2>
            <?=$translation['description_3']?>
        </div>
        <div class="column_1 imgContent">
            <img class="lazyLoad" src="/images/ScreenXRecorder-01.webp" width="1000px" height="664px" alt="<?=$translation['title_3']?>" title="<?=$translation['title_3']?>" loading="lazy">
        </div>
    </div>
</section>
<section class="section section_4">
    <div class="row_0">
        <div class="column_0">
            <h2><?=$translation['title_4']?></h2>
            <?=$translation['description_4']?>
        </div>
        <div class="column_1 imgContent">
            <img class="lazyLoad" src="/images/ScreenXRecorder-02.webp" width="1000px" height="638px" alt="<?=$translation['title_4']?>" title="<?=$translation['title_4']?>" loading="lazy">
        </div>        
    </div>
</section>
<section class="section section_5">
    <div class="row_0">
        <div class="column_0">
            <h2><?=$translation['title_5']?></h2>
            <?=$translation['description_5']?>
        </div>
        <div class="column_1 imgContent">
            <img class="lazyLoad" src="/images/ScreenXRecorder-03.webp" width="1000px" height="638px" alt="<?=$translation['title_5']?>" title="<?=$translation['title_5']?>" loading="lazy">
        </div>        
    </div>
</section>
<section class="section section_6">
    <div class="row_0">
        <div class="column_0">
            <h2><?=$translation['title_6']?></h2>
            <?=$translation['description_6']?>
        </div>
        <div class="column_1 imgContent">
            <img class="lazyLoad" src="/images/ScreenXRecorder-04.webp" width="1000px" height="634px" alt="<?=$translation['title_6']?>" title="<?=$translation['title_6']?>" loading="lazy">
        </div>        
    </div>
</section>
<dialog id="returnDialog" aria-describedby="returnDialogText">
    <form method="dialog">
        <p id="returnDialogText"><?=$translation['dialogReturn']?></p>
        <div class="button-group">
            <button type="submit" value="confirm" aria-label="<?=$translation['agree']?>"><?=$translation['agree']?></button>
            <button type="submit" value="cancel" aria-label="<?=$translation['close']?>"><?=$translation['close']?></button>
        </div>
    </form>
</dialog>  
<dialog id="screenCaptureDialog">
    <p><?=$translation['dialogScreenCapture']?></p>
    <form method="dialog">
        <button id="screenCaptureDialogClose" class="buttonConfirm">
            <?=$translation['agree']?>
        </button>
    </form>
</dialog>          
<dialog id="infoUsePicture">
    <p><?=$translation['dialogPictureInPicture']?></p>
    <img class="lazyLoad" src="images/cameraPIPUse.gif" alt="<?=$translation['dialogPictureInPictureAltImg']?>" width="300px" height="204px" loading="lazy">
    <label>
        <input type="checkbox" id="infoUsePictureNoShowAgain">
        <span><?=$translation['noShowAgain']?></span>
    </label>
    <form method="dialog">
        <button class="buttonConfirm">
            <?=$translation['agree']?>
        </button>
    </form>
</dialog>
<dialog id="infoUseMicrophone">
    <p><?=$translation['dialogInfoUseMicrophone']?></p>
    <img class="lazyLoad" src="images/microphoneUse.gif" alt="<?=$translation['dialogInfoUseMicrophoneAltImg']?>" width="300px" height="204px" loading="lazy">
    <label>
        <input type="checkbox" id="infoUseMicrophoneNoShowAgain">
        <span><?=$translation['noShowAgain']?></span>
    </label>
    <form method="dialog">
        <button class="buttonConfirm">
            <?=$translation['agree']?>
        </button>
    </form>
</dialog>
<div id="infoScreen" class="infoScreen" style="display:none;">
    <div class="step1Screen">
        <p><?=$translation['step1Screen']?></p>
    </div>
    <div class="step2Screen">
        <p><?=$translation['step2Screen']?></p>
    </div>
</div>