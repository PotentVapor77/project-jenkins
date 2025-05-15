<?php
namespace Vendor\VentasPhp\Core;

class Response {
    private string $body;
    private int $status;
    private array $headers;

    public static function redirect(string $url, int $code = 302): self {
        return new self('', $code, ['Location' => $url]);
    }

    public function __construct(string $body = '', int $status = 200, array $headers = []) {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo $this->body;
    }
}
