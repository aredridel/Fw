<?php

namespace Fw;

class Response {
    protected $headers = array();
    protected $headersSent = false;
    protected $writeStarted = true;
    protected $statusCode = "200 OK";
    protected $body = '';

    public static function createInstance() {
        return new static();
    }

    public function redirect($url) {
        header("Location: $url");
    }

    public function setHeader($key, $value) {
        if ($this->headersSent) throw new LogicError("Headers have already been sent");
        $this->headers[$key] = $value;
    }

    public function setStatusCode($code) {
        if ($this->headersSent) throw new LogicError("Headers have already been sent");
        $this->statusCode = $code;
    }

    public function sendHeaders() {
        if ($this->headersSent) throw new LogicError("Headers have already been sent");

        if ($this->statusCode != 200) {
            header("HTTP/1.1 {$this->statusCode}");
        }

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        $this->headersSent = true;
    }

    public function send() {
        if (!$this->headersSent) $this->sendHeaders();
        if (!$this->writeStarted) {
            print($this->body);
            $this->writeStarted = true;
        }
    }

    public function write($output) {
        if ($this->writeStarted) {
            print($output);
        } else {
            $this->body .= $output;
        }
    }
}
