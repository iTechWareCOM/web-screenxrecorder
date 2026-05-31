<?php
class IconsModel {
    private $filePath;

    public function __construct() {
        $this->filePath = RESOURCES . 'Icons/icons.json';
    }

    /**
     * Lee el JSON de iconos y lo devuelve como array asociativo.
     * Lanza excepción en vez de devolver null para que el renderizado
     * no falle silenciosamente si falta el archivo.
     *
     * @return array
     * @throws Exception
     */
    public function getIconsData() {
        if (!file_exists($this->filePath)) {
            throw new Exception("Icons file not found: " . $this->filePath);
        }
        return json_decode(file_get_contents($this->filePath), true);
    }
}
