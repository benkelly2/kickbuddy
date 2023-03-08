<?php

use FirebaseJWT\JWT;

/**
 * Authenticate username and password
 * 
 * @author Ben Kelly w19014367
 */

class Login extends Endpoint {

    public function __construct() {
        
        /** Connect to database */
        $db = new Database("db/kickbuddy.sqlite");
        /** Check request method is POST */
        $this->validateRequestMethod("POST");
        /** Check there is a username and password parameter */
        $this->validateAuthParameters();

        /** Execute SQL query to select the user */
        $sql = "SELECT userID, username, password
        FROM users
        WHERE username = :username";
        $params = [":username" => $_SERVER['PHP_AUTH_USER']];

        $queryResult = $db->executeSQL($sql, $params);

        /** Validate username */
        $this->validateUsername($queryResult);
        /** Validate password */
        $this->validatePassword($queryResult);                   

        $data['token'] = $this->createJWT($queryResult);

        $this->setData(array(
            "auth" => true,
            "message" => "success",
            "data" => $data
        ));
    }

    /** Function to validate request method - make array if want to allow multiple methods */
    private function validateRequestMethod($method) {
        if($_SERVER['REQUEST_METHOD'] != $method) {
            throw new ClientErrorException("Invalid request method", 400);
        }
    }

    /** Function to validate authorization parameters */
    private function validateAuthParameters() {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            throw new ClientErrorException("Username and password required", 401);
        }
    }

    /** Function to validate username */
    private function validateUsername($data) {
        if(count($data) < 1) {
            throw new ClientErrorException("Invalid credentialsUSERNAME");
        }
    }
    /////////////////////////////////// UNCOMMENT WHEN WORKING ////////////////////////////////////////
    /** Function to validate password */
    // private function validatePassword($data) {

    //     // password_verify is matching PHP_AUTH_PW (the pw the user enters) with the HASH of the password in DB
    //     // so need to figure out how to compare the HASH of the user's pw with the HASH of the pw in DB.
    //     if(!password_verify($_SERVER['PHP_AUTH_PW'], $data[0]['password'])) {
    //         throw new ClientErrorException("Invalid credentialsPASSWORD. + PHP_AUTH_PW: ".$_SERVER['PHP_AUTH_PW']." needs to match: ". $data[0]['password']);
    //     }
    // }

    // TEMP FUNCTION TO VALIDATE PASSWORD - WEAK - CANNOT KEEP LONG-TERM
    private function validatePassword($data) {
        if($_SERVER['PHP_AUTH_PW'] !== $data[0]['password']) {
            throw new ClientErrorException("Invalid credentialsPASSWORDTEMP");
        }
    }

/////////////////////////////////// UNCOMMENT WHEN WORKING ////////////////////////////////////////



    /** Function to create JWT */
    private function createJWT($queryResult) {
        $secretKey = SECRET;
        $time = time();

        $tokenPayload = [
            "iat" => $time,                                 /** Issued at */
            "exp" => strtotime('+1 day', $time),            /** Expires at */
            "iss" => $_SERVER['HTTP_HOST'],                 /** Issuer */
            "userID" => $queryResult[0]['userID'],          /** User's ID */
            "username" => $queryResult[0]['username'],      /** User's username */
        ];

        $jwt = JWT::encode($tokenPayload, $secretKey, 'HS256');

        return $jwt;
    }
    
}