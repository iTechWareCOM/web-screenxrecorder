<?php
class EmailSender {
    private $to;
    private $subject = "New Contact Message";

    public function __construct() {
        $this->to = $_ENV['CONTACT_EMAIL'] ?? '';
    }

    /**
     * Valida y envía el formulario de contacto con mail() de PHP.
     * Sanea todos los campos y elimina saltos de línea en la cabecera From
     * para evitar inyección de cabeceras. Devuelve una respuesta JSON
     * tanto en éxito como en error.
     */
    public function send() {
        try {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                return $this->response("error", "Invalid request method.");
            }
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $supportOption = $this->sanitizeInput($_POST['support-options'] ?? '');
            $message = $this->sanitizeInput($_POST['message'] ?? '');
            
            if (empty($name) || empty($email) || empty($supportOption) || empty($message)) {
                return $this->response("error", "All fields are required.");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->response("error", "Invalid email format.");
            }

            $body = "Name: $name\n";
            $body .= "Email: $email\n";
            $body .= "Support Option: $supportOption\n";
            $body .= "Message:\n$message\n";
            $nameHeader = preg_replace('/[\r\n]/', '', $name);
            $headers = "From: $nameHeader <$email>\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
            
            if (mail($this->to, $this->subject, $body, $headers)) {
                return $this->response("success", "Email sent successfully.");
            } else {
                return $this->response("error", "Error sending email.");
            }
        } catch (Exception $e) {
            return $this->response("error", "Exception: " . $e->getMessage());
        }
    }
    
    /**
     * Elimina espacios en los extremos y escapa caracteres HTML.
     * Suficiente para estos campos ya que nada se persiste en base de datos,
     * solo va al cuerpo de un correo.
     *
     * @param string $data
     * @return string
     */
    private function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }
    
    /**
     * Emite una respuesta JSON y detiene la ejecución.
     *
     * @param string $status   'success' o 'error'
     * @param string $message
     */
    private function response($status, $message) {
        header('Content-Type: application/json');
        echo json_encode(["status" => $status, "message" => $message]);
        exit();
    }
}
