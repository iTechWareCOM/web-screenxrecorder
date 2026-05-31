        </main>
        <footer>
            <div class="wrapperContent alignCenter">
                <div class="legal">
                    <a href="<?=$baseUrl?><?=$translation['lang']?>/terms/" target="_blank" rel="noopener noreferrer"><?=$translation['terms']?></a>
                    <a href="<?=$baseUrl?><?=$translation['lang']?>/privacy/" target="_blank" rel="noopener noreferrer"><?=$translation['privacy']?></a>
                    <a href="<?=$baseUrl?><?=$translation['lang']?>/cookies-policy/" target="_blank" rel="noopener noreferrer"><?=$translation['cookiesPolicy']?></a>
                    <a href="<?=$baseUrl?><?=$translation['lang']?>/legal/" target="_blank" rel="noopener noreferrer"><?=$translation['legal']?></a>
                    <a href="<?=$baseUrl?><?=$translation['lang']?>/faq/" target="_blank" rel="noopener noreferrer"><?=$translation['faq']?></a>
                </div>
                <p class="copyright">
                    Ver <?=VERSION_APP?> | &copy; <?=date("Y") . ' ' . $translation['copyright']?>
                </p>
                <p class="creator-social">
                    <a href="https://www.linkedin.com/in/ismael-flores-rubio/" target="_blank" rel="noopener noreferrer" aria-label="Ismael Flores - LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        Ismael Flores
                    </a>
                </p>
            </div>
        </footer>
        <div class="modalBackground" data-label="modalBackground" style="display:none;"></div>
        <div class="lightsContent"></div>
        <div class="floatButton go_back_up" role="button" tabindex="0" aria-label="<?=$translation['goBackUp']?>">
            <svg viewBox="0 0 24 24"><use href="#icon-go-back-up"></use></svg>
        </div>
        <div class="floatButton report" role="button" tabindex="0" aria-label="<?=$translation['report']?>">
            <svg viewBox="0 0 24 24"><use href="#icon-report"></use></svg>
        </div>
        <dialog id="contactDialog" aria-labelledby="contactDialogTitle" role="dialog" aria-modal="true">
            <form id="contactForm" method="dialog" aria-describedby="contactDialogDescription">
                <h2 id="contactDialogTitle"><?=$translation['contactDialogTitle']?></h2>
                <p id="contactDialogDescription"><?=$translation['contactDialogDescription']?></p>
                <label for="name"><?=$translation['contactDialogName']?></label>
                <input type="text" id="name" name="name" required aria-required="true" aria-labelledby="nameLabel" aria-describedby="nameDescription">
                <p id="nameDescription" class="sr-only"><?=$translation['contactDialogNameDescription']?></p>
                <label for="support-options" id="supportOptionsLabel" class="support-label"><?=$translation['contactDialogSupportOptionsLabel']?></label>
                <select id="support-options" name="support-options" aria-labelledby="supportOptionsLabel" aria-required="true" required>
                    <option value="" disabled selected aria-disabled="true"><?=$translation['SupportOptionsNull']?></option>
                    <option value="suggestions" aria-label="<?=$translation['SupportOptionsSuggestions']?>"><?=$translation['SupportOptionsSuggestions']?></option>
                    <option value="translation-errors" aria-label="<?=$translation['SupportOptionsTranslationErrors']?>"><?=$translation['SupportOptionsTranslationErrors']?></option>
                    <option value="recording-issues" aria-label="<?=$translation['SupportOptionsRecordingIssues']?>"><?=$translation['SupportOptionsRecordingIssues']?></option>
                    <option value="quality-issues" aria-label="<?=$translation['SupportOptionsQualityIssues']?>"><?=$translation['SupportOptionsQualityIssues']?></option>
                    <option value="browser-compatibility" aria-label="<?=$translation['SupportOptionsBrowserCompatibility']?>"><?=$translation['SupportOptionsBrowserCompatibility']?></option>
                    <option value="device-setup" aria-label="<?=$translation['SupportOptionsDeviceSetup']?>"><?=$translation['SupportOptionsDeviceSetup']?></option>
                    <option value="lag-freezing" aria-label="<?=$translation['SupportOptionsLagFreezing']?>"><?=$translation['SupportOptionsLagFreezing']?></option>
                    <option value="device-access" aria-label="<?=$translation['SupportOptionsDeviceAccess']?>"><?=$translation['SupportOptionsDeviceAccess']?></option>
                    <option value="feature-request" aria-label="<?=$translation['SupportOptionsFeatureRequest']?>"><?=$translation['SupportOptionsFeatureRequest']?></option>
                    <option value="privacy-security" aria-label="<?=$translation['SupportOptionsPrivacySecurity']?>"><?=$translation['SupportOptionsPrivacySecurity']?></option>
                    <option value="general-errors" aria-label="<?=$translation['SupportOptionsGeneralErrors']?>"><?=$translation['SupportOptionsGeneralErrors']?></option>
                </select>
                <label for="email" id="emailLabel"><?=$translation['contactDialogEmailLabel']?></label>
                <input type="email" id="email" name="email" required aria-required="true" aria-describedby="emailDescription">
                <p id="emailDescription" class="sr-only"><?=$translation['contactDialogEmailDescription']?></p>
                <label for="message" id="messageLabel"><?=$translation['contactDialogMessageLabel']?></label>
                <textarea id="message" name="message" required aria-required="true" aria-labelledby="messageLabel" aria-describedby="messageDescription"></textarea>
                <p id="messageDescription" class="sr-only"><?=$translation['contactDialogMessageDescription']?></p>
                <div id="captchaContainer" role="group" aria-labelledby="captchaLabel">
                    <p id="captchaLabel"><?=$translation['contactDialogCaptchaLabel']?></p>
                    <p id="captchaQuestion" aria-live="polite"><?=$translation['contactDialogCaptchaQuestion']?></p>
                    <input type="text" id="captchaAnswer" required aria-required="true" aria-labelledby="captchaLabel" aria-describedby="captchaDescription" placeholder="<?=$translation['contactDialogCaptchaPlaceholder']?>">
                    <p id="captchaDescription" class="sr-only"><?=$translation['contactDialogCaptchaDescription']?></p>
                </div>
                <p id="contactDialogSuccess"><?=$translation['contactDialogSuccess']?></p>
                <p id="contactDialogError"><?=$translation['contactDialogError']?></p>
                <div class="button-group">
                    <button type="submit" aria-label="<?=$translation['contactDialogSubmitButtonAriaLabel']?>"><?=$translation['contactDialogSubmitButtonText']?></button>
                    <button class="closeButton" aria-label="<?=$translation['contactDialogCloseButtonAriaLabel']?>"><?=$translation['contactDialogCloseButtonText']?></button>
                </div>
            </form>
        </dialog>
    </body>
</html>