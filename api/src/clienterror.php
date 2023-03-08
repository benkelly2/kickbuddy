<?php
/**
 * Endpoint for handling incorrect input from user
 * 
 * @author Ben Kelly w19014367
 */
class ClientError extends Endpoint {
    public function __construct($message="", $code=400) {
        http_response_code($code);

        $this->setData( array(
            "length" => 0,
            "message" => $message,
            "data" => null
        ));
    }
}