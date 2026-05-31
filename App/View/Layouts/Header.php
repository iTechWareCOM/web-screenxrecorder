<!DOCTYPE html>
<html lang="<?=isset($translation['lang']) ? htmlspecialchars($translation['lang'], ENT_QUOTES, 'UTF-8') : 'en'?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=htmlspecialchars($translation['title'], ENT_QUOTES, 'UTF-8')?></title>
    <base href="<?=$baseUrl?>"/>
    <?php if (!empty($_ENV['GTM_ID'])): ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?=htmlspecialchars($_ENV['GTM_ID'],ENT_QUOTES,'UTF-8')?>');</script>
    <?php endif; ?>
    <?php if (!empty($_ENV['GA4_ID'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=htmlspecialchars($_ENV['GA4_ID'],ENT_QUOTES,'UTF-8')?>"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?=htmlspecialchars($_ENV['GA4_ID'],ENT_QUOTES,'UTF-8')?>');</script>
    <?php endif; ?>
    <link rel="preload" href="/fonts/Saira-Light.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/Saira-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/Saira-Medium.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/fonts/Saira-SemiBold.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <script src="scripts/themeSelector.js?v=<?=VERSION_APP?>" defer></script>
    <script src="scripts/goBackUp.js?v=<?=VERSION_APP?>" defer></script>
    <script src="scripts/langSelector.js?v=<?=VERSION_APP?>" defer></script>
    <script src="scripts/contactForm.js?v=<?=VERSION_APP?>" defer></script>
    <script src="scripts/emailCopy.js?v=<?=VERSION_APP?>" defer></script>
    <script type="importmap">
    {
      "imports": {
        "./scripts/module/lightsBackground.js": "./scripts/module/lightsBackground.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/indexMR.js": "./scripts/module/MediaRecorder/indexMR.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/globals.js": "./scripts/module/MediaRecorder/globals.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/controlsRecorder.js": "./scripts/module/MediaRecorder/controlsRecorder.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/fixWebmDuration.js": "./scripts/module/MediaRecorder/fixWebmDuration.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/screen/indexScreen.js": "./scripts/module/MediaRecorder/screen/indexScreen.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/microphone/indexMicrophone.js": "./scripts/module/MediaRecorder/microphone/indexMicrophone.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/microphone/listMicrophones.js": "./scripts/module/MediaRecorder/microphone/listMicrophones.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/camera/indexCamera.js": "./scripts/module/MediaRecorder/camera/indexCamera.js?v=<?=VERSION_APP?>",
        "./scripts/module/MediaRecorder/camera/listCameras.js": "./scripts/module/MediaRecorder/camera/listCameras.js?v=<?=VERSION_APP?>"
      }
    }
    </script>
    <script type="module" src="scripts/index.js?v=<?=VERSION_APP?>"></script>
    <link rel="stylesheet" type="text/css" href="css/globalStyle.css?v=<?=VERSION_APP?>">
    <meta name="description" content="<?=$translation['metaOgDescription']?>" />
    <link rel="canonical" href="<?=htmlspecialchars("https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", ENT_QUOTES, 'UTF-8')?>" />
    <meta property="og:title" content="<?=$translation['title']?>" />
    <meta property="og:description" content="<?=$translation['metaOgDescription']?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?=htmlspecialchars("https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", ENT_QUOTES, 'UTF-8')?>" />
    <meta property="og:image" content="<?=$baseUrl?>images/og_ScreenXRecorder_1200x630.webp" />
    <meta property="og:image:alt" content="<?=$translation['metaOgDescription']?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:site_name" content="ScreenXRecorder" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?=htmlspecialchars($translation['title'], ENT_QUOTES, 'UTF-8')?>" />
    <meta name="twitter:description" content="<?=htmlspecialchars($translation['metaOgDescription'], ENT_QUOTES, 'UTF-8')?>" />
    <meta name="twitter:image" content="<?=$baseUrl?>images/og_ScreenXRecorder_1200x630.webp" />
    <meta name="twitter:image:alt" content="<?=$translation['metaOgDescription']?>" />
    <link rel="icon" type="image/x-icon" sizes="48x48" href="favicon.ico" />
    <link rel="apple-touch-icon" href="favicon.ico" />
    <meta name="author" content="<?=htmlspecialchars($translation['metaAuthor'], ENT_QUOTES, 'UTF-8')?>" />
    <meta name="creator" content="Ismael Flores" />
    <meta name="publisher" content="iTechWare.com" />
    <meta name="theme-color" content="#56a7ef" />
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <link rel="me" href="https://www.linkedin.com/in/ismael-flores-rubio/" />
    <?=$hreflang?>
    <script type="application/ld+json">{"@context": "https://schema.org","@type": "WebApplication","name": "<?=$translation['title']?>","inLanguage": "<?=$translation['lang']?>","operatingSystem": "Windows, macOS, Linux","applicationCategory": "Utility","description": "<?=$translation['metaDescription']?>","url": "<?=$translation['metaOgUrl']?>","author": {"@type": "Person","name": "Ismael Flores","url": "https://www.linkedin.com/in/ismael-flores-rubio/","sameAs": ["https://www.linkedin.com/in/ismael-flores-rubio/","https://itechware.com"]},"keywords": "<?=$translation['metaKeywords']?>","publisher": {"@type": "Person","name": "<?=$translation['metaAuthor']?>","logo": {"@type": "ImageObject","url": "<?=$baseUrl?>images/logo_60x60.png","width": 60,"height": 60}},"headline": "<?=$translation['title']?>","mainEntityOfPage": "<?=$translation['metaOgUrl']?>","image": {"@type": "ImageObject","url": "<?=$baseUrl?>images/og_ScreenXRecorder_1200x630.webp","width": 1200,"height": 630},"offers": {"@type": "Offer","price": "0","priceCurrency": "USD"}}</script>
</head>
<!--------------------------------------------------
    |\---/|
    | ,_, |     _ ______        __ _      __
     \=`=/-.._ (_)_  __/__ ____/ /| | /| / /__  _______
  ___/   '    / / / / / -_) __/ _ \ |/ |/ / _ `/ __/ -_)
 (__...'     /_/ /_/  \__/\__/_//_/__/|__/\_,_/_/  \__/ 
   (_,...'(_,.. Ismael Flores . itechware.com . #CATLOVER
--------------------------------------------------->
<body>
    <?php include APP . 'View/Layouts/SvgSprite.php'; ?>
    <?php if (!empty($_ENV['GTM_ID'])): ?><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?=htmlspecialchars($_ENV['GTM_ID'],ENT_QUOTES,'UTF-8')?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><?php endif; ?>
    <header>
        <a href="/<?=$translation['lang']?>/" aria-label="<?=$translation['ariaLogo']?>">
            <div class="logo">
                <span class="screenX">Screen<i>X</i></span>
                <span>RECORDER</span>
            </div>
        </a>
        <div class="nav">
            <button class="lang_selector" aria-haspopup="dialog" aria-expanded="false" aria-controls="langDialog" id="lang-selector" aria-label="<?=$translation['ariaLanguage']?>">
                <svg fill="currentColor" viewBox="0 0 55 65.6" xmlns="http://www.w3.org/2000/svg"><use href="#icon-lang"></use></svg>
                <span><?=$translation['lang']?></span>
            </button>
            <div class="theme_selector">
                <button data-theme="system" class="theme_option auto_theme" aria-label="<?=$translation['ariaThemeSystem']?>" style="pointer-events: auto;">
                    <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><use href="#icon-theme-auto"></use></svg>
                </button>
                <button class="theme_option light_theme" aria-label="<?=$translation['ariaThemeLight']?>" data-theme="light" style="pointer-events: none;">
                    <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><use href="#icon-theme-light"></use></svg>
                </button>
                <button class="theme_option dark_theme" aria-label="<?=$translation['ariaThemeDark']?>" data-theme="dark" style="pointer-events: none;">
                    <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><use href="#icon-theme-dark"></use></svg>
                </button>
            </div>
        </div>
        <dialog id="langDialog" class="langDialog">
            <?=$listlang?>
            <form method="dialog">
                <button class="closeButton"><?=$translation['close']?></button>
            </form>
        </dialog>
    </header>
    <main>