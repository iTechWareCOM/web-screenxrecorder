<?php
class TranslateModel {
    private $filePath;

    public function __construct() {
        $this->filePath = DATA_LANG_FOLDER;
    }

    /**
     * Carga el JSON de traducción para el idioma y página indicados.
     * Si no existe el archivo del idioma solicitado, cae en inglés como fallback
     * para no romper la página por una traducción incompleta.
     *
     * @param string $currentLanguage  Ej: 'es', 'fr'
     * @param string $currentPage      Nombre de la vista, ej: 'Home', 'Faq'
     * @return array
     * @throws \Exception  Si tampoco existe el fallback en inglés
     */
    public function getTranslateData($currentLanguage, $currentPage) {
        $filePath = $this->filePath . $currentLanguage . '/' . $currentPage . '.json';
        if (!file_exists($filePath)) {
            $filePath = $this->filePath . 'en/' . $currentPage . '.json';
            if (!file_exists($filePath)) {
                throw new \Exception("Translation file not found: " . $filePath);
            }
        }
        return json_decode(file_get_contents($filePath), true);
    }

    /**
     * Comprueba si existe el directorio del idioma bajo Resources/Translate/.
     * El router lo usa para rechazar slugs de locale inválidos antes de intentar
     * cargar cualquier archivo de traducción.
     *
     * @param string $language
     * @return bool
     */
    public function isValidLanguage($language) {
        $languageDir = ROOT . "Resources/Translate/" . $language;
        return is_dir($languageDir);
    }

    /**
     * Carga la traducción de la cabecera o el pie para un idioma concreto.
     * Devuelve array vacío si el archivo no existe; las plantillas del layout
     * tratan las claves ausentes como cadena vacía.
     *
     * @param string $language
     * @param string $type  'Header' o 'Footer'
     * @return array
     */
    public function getHeaderFooterTranslation($language, $type) {
        $file = ROOT . "Resources/Translate/" . $language . "/$type.json";
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }
}