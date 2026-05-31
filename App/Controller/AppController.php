<?php
require_once APP . '/Model/IconsModel.php';
require_once APP . '/Model/TranslateModel.php';
require_once APP . '/Model/HreflangModel.php';
require_once APP . '/EndPoint/SendMail.php';
require_once APP . '/View/Helpers/HtmlHelper.php';

class AppController {
    protected $language;
    protected $template;
    protected $translateModel;
    protected $iconsModel;
    protected $hreflangModel;
    protected $sendMail;
    public function __construct() {
        $this->translateModel = new TranslateModel();
        $this->iconsModel = new IconsModel();
        $this->hreflangModel = new HreflangModel();
        $this->sendMail = new EmailSender();
    }

    /**
     * Punto de entrada de cada petición. Parsea la URL en /{lang}/{template},
     * valida el idioma y despacha al endpoint send-email o renderiza
     * la página estática correspondiente. Las rutas desconocidas redirigen a /{lang}/.
     */
    public function run() {
        $url = $this->getUrl();
        $urlParts = explode('/', $url);
        $this->language = $urlParts[0] ?? 'en';
        $this->template = $urlParts[1] ?? '';

        //EndPoints
        if ($urlParts[0] === 'send-email') {
            $this->sendMail->send();
            exit();
        }

        //Web
        if (empty($this->language)) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: /en/");
            exit();
        }
        if (!$this->translateModel->isValidLanguage($this->language)) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: /en/");
            exit();
        }
        if ($this->template == '') {
            self::render('Home');
            exit();
        }

        $staticPages = [
            'terms'          => 'Terms',
            'privacy'        => 'Privacy',
            'legal'          => 'Legal',
            'faq'            => 'Faq',
            'cookies-policy' => 'CookiesPolicy',
        ];
        if (array_key_exists($this->template, $staticPages)) {
            self::render($staticPages[$this->template]);
            exit();
        }

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /{$this->language}/");
        exit();
    }

    /**
     * Monta y emite la página completa: cabecera, vista de contenido y pie.
     * Los campos meta de la vista (título, descripción, og:url) sobreescriben
     * los valores por defecto que vienen del archivo de traducción del Header.
     *
     * @param string $view  Nombre de la clase de vista, ej: 'Home', 'Faq'
     */
    public function render($view) {
        $baseUrl = self::baseUrl();
        $icons = (new IconsModel())->getIconsData();

        $translation = $this->processEmailPlaceholder(
            $this->translateModel->getTranslateData($this->language, $view)
        );
        $headerTranslation = $this->translateModel->getHeaderFooterTranslation($this->language, 'Header');

        foreach (['title' => 'pageTitle', 'metaDescription' => 'metaDescription', 'metaOgDescription' => 'metaOgDescription', 'metaOgUrl' => 'metaOgUrl'] as $headerKey => $pageKey) {
            if (!empty($translation[$pageKey])) {
                $headerTranslation[$headerKey] = $translation[$pageKey];
            }
        }

        $staticPageSlugs = [
            'Terms' => 'terms', 'Privacy' => 'privacy', 'Legal' => 'legal',
            'Faq' => 'faq', 'CookiesPolicy' => 'cookies-policy'
        ];

        if (array_key_exists($view, $staticPageSlugs)) {
            $slug = $staticPageSlugs[$view];
            $hreflang  = '<link rel="alternate" href="' . $baseUrl . 'en/' . $slug . '/" hreflang="en"/>';
            $hreflang .= '<link rel="alternate" href="' . $baseUrl . 'es/' . $slug . '/" hreflang="es"/>';
            $hreflang .= '<link rel="alternate" href="' . $baseUrl . 'en/' . $slug . '/" hreflang="x-default"/>';
        } else {
            $hreflang = '';
            foreach ($this->hreflangModel->getHreflangData() as $hreflangItem) {
                $hreflang .= '<link rel="alternate" href="' . $baseUrl . $hreflangItem['url'] . '" hreflang="' . $hreflangItem['lang'] . '"/>';
            }
            $hreflang .= '<link rel="alternate" href="' . $baseUrl . 'en/" hreflang="x-default"/>';
        }
        $topLangs   = ['en', 'zh', 'es', 'ar', 'pt'];
        $allLangs   = $this->hreflangModel->getHreflangData();
        $langIndex  = array_column($allLangs, null, 'lang');

        $listlang = '<ul id="langList">';
        foreach ($topLangs as $code) {
            if (!isset($langIndex[$code])) continue;
            $item = $langIndex[$code];
            $listlang .= '<li class="lang-featured"><a href="' . $baseUrl . $item['url'] . '" title="' . $item['nameLang'] . '"><img src="' . $baseUrl . 'images/flags/' . $item['lang'] . '.svg" loading="lazy" width="26px" height="26px">' . $item['nameLang'] . '</a></li>';
        }
        $listlang .= '<li class="lang-separator" role="separator"><span></span></li>';
        foreach ($allLangs as $item) {
            if (in_array($item['lang'], $topLangs)) continue;
            $listlang .= '<li><a href="' . $baseUrl . $item['url'] . '" title="' . $item['nameLang'] . '"><img src="' . $baseUrl . 'images/flags/' . $item['lang'] . '.svg" loading="lazy" width="26px" height="26px">' . $item['nameLang'] . '</a></li>';
        }
        $listlang .= '</ul>';
        self::loadView('Layouts/Header', $baseUrl, $headerTranslation, $icons, $hreflang, $listlang);

        self::loadView($view, $baseUrl, $translation, $icons, null, null);

        $footerTranslation = $this->translateModel->getHeaderFooterTranslation($this->language, 'Footer');
        self::loadView('Layouts/Footer', $baseUrl, $footerTranslation, $icons, null, null);
        exit;
    }

    /**
     * Incluye el archivo de vista con las variables necesarias en su scope.
     * El $baseUrl se escapa aquí una sola vez para que las plantillas no tengan que hacerlo.
     *
     * @param string      $view
     * @param string      $baseUrl
     * @param array       $translation
     * @param array       $icons
     * @param string|null $hreflang
     * @param string|null $listlang
     * @throws Exception  Si el archivo de vista no existe o no es legible
     */
    private static function loadView($view, $baseUrl = '', $translation = [], $icons=[], $hreflang, $listlang) {
        $file = APP . "View/{$view}.php";
        if (is_readable($file)) {
            $baseUrl = htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8');
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Construye la URL base desde la petición actual, eliminando la ruta
     * de la carpeta pública para que funcione tanto en la raíz del dominio
     * como en un subdirectorio.
     *
     * @return string  Ej: 'https://ejemplo.com/'
     */
    public static function baseUrl(){
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
        $baseUrl .= '://'.$_SERVER['HTTP_HOST'];
        $baseUrl .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $baseUrl = str_replace(PUBLIC_FOLDER . '/', '', $baseUrl);
        return $baseUrl;
    }
    
    public static function getIconsData() {
        $iconsModel = new IconsModel();
        return $iconsModel->getIconsData();
    }

    /**
     * Lee y limpia el parámetro 'url' de la query string.
     * El .htaccess redirige todas las peticiones a index.php?url=...
     *
     * @return string
     */
    protected function getUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        return trim($url, '/');
    }

    /**
     * Sustituye el placeholder {{CONTACT_EMAIL}} en los valores de traducción
     * por un <button> con el email codificado en base64 invertido en data-e.
     *
     * El email nunca aparece en texto plano en el HTML como atributo href,
     * lo que dificulta su extracción por bots de spam. El JS de emailCopy.js
     * decodifica data-e y copia el valor al portapapeles al hacer click.
     *
     * Si CONTACT_EMAIL no está definido en .env, devuelve el array sin cambios.
     *
     * @param  array $data  Array de traducción cargado desde JSON
     * @return array        Array con los placeholders reemplazados
     */
    private function processEmailPlaceholder(array $data): array {
        $email = $_ENV['CONTACT_EMAIL'] ?? '';
        if (empty($email)) return $data;
        $footer    = $this->translateModel->getHeaderFooterTranslation($this->language, 'Footer');
        $copyTitle = $footer['copyEmailTitle'] ?? 'Copy email';
        $button    = HtmlHelper::emailButton($email, $copyTitle);
        array_walk_recursive($data, function (&$v) use ($button) {
            if (is_string($v)) $v = str_replace('{{CONTACT_EMAIL}}', $button, $v);
        });
        return $data;
    }
}
