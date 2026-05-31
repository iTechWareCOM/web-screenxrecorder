<?php

class HreflangModel{
    private $filePath;

    public function __construct() {
        $this->filePath = DATA_LANG_FOLDER;
    }

    /**
     * Devuelve la configuración completa de hreflang desde languages.json.
     * El controlador usa estos datos para generar las etiquetas
     * <link rel="alternate"> y la lista del selector de idioma.
     *
     * @return array
     * @throws \Exception
     */
    public function getHreflangData() {
        $filePath = $this->filePath . '/languages.json';
        if (!file_exists($filePath)) {
            throw new \Exception("Translation file not found: " . $filePath);
        }
        return json_decode(file_get_contents($filePath), true);
    }
}