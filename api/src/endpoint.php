<?php

/**
 * Endpoints class
 * 
 * @author Ben Kelly w19014367
 */
abstract class Endpoint {
    private $data;

    public function __construct() {
        $db = new Database("db/kickbuddy.sqlite");

        $this->setData( array(
            "message" => "Success",
        ));
    }

    // add endpointParams() function here

    // add validateParams($params) function here

    protected function setData($data) {
        $this->data = $data;
    }
    public function getData() {
        return $this->data;
    }

}