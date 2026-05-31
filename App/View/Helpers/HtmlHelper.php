<?php
class HtmlHelper {

    /**
     * Genera el botón de copiar email con el email obfuscado en base64 invertido.
     *
     * El email se almacena en data-e como base64(strrev(email)) para dificultar
     * su extracción por bots de spam. emailCopy.js decodifica y copia al portapapeles.
     *
     * @param  string $email      Dirección de correo en texto plano
     * @param  string $copyTitle  Texto del atributo title (traducido)
     * @return string             Elemento <button> HTML listo para insertar
     */
    public static function emailButton(string $email, string $copyTitle): string {
        $b64 = base64_encode(strrev($email));
        return '<button class="copy-email"'
            . ' data-e="' . $b64 . '"'
            . ' title="' . htmlspecialchars($copyTitle, ENT_QUOTES, 'UTF-8') . '">'
            . htmlspecialchars($email, ENT_QUOTES, 'UTF-8')
            . '</button>';
    }
}
