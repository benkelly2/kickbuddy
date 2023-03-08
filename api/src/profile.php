<?php

use FirebaseJWT\JWT;
use FirebaseJWT\Key;

/**
 * Profile Endpoint
 * 
 * @author Ben Kelly w19014367
 */

class Profile extends Endpoint {

    public function __construct() {
        try {
            http_response_code(200);

            /** Validate the request method, allowing only GET */
            $this->validateRequestMethod("GET");

            /** Validate the JWT */
            // $this->validateToken();

            $db = new Database("db/kickbuddy.sqlite");
            

            
            $sql = "SELECT players.userID, players.playerID, players.name, players.gamesPlayed, players.wins, players.draws, players.losses, players.goals, players.assists, players.avgRating, users.username
                    FROM players
                    JOIN users ON users.playerID = players.playerID"; 
            $params = [];

            $queryResult = $db->executeSQL($sql, $params);

            $this->setData(array(
                "length" => count($queryResult),
                "message" => "Success",
                "data" => $queryResult
            ));


        } catch (PDOException $ex) {
            /** 500 - Generic error response */
            http_response_code(500);
            echo ($ex);
        }
    }

    /** Function to validate the request method, allowing only GET */
    private function validateRequestMethod($method) {
        if($_SERVER['REQUEST_METHOD'] != $method) {
            throw new ClientErrorException("Invalid Request Method", 405);
        }
    }

    /** Function to validate the JWT */
    private function validateToken() {
        $key = SECRET;

        $allHeaders = getallheaders();
        $authorizationHeader = "";

        if(array_key_exists('Authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['Authorization'];
        } else if (array_key_exists('authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['authorization'];
        }

        if(substr($authorizationHeader, 0, 7) != 'Bearer') {
            throw new ClientErrorException("Bearer token required", 401);
        }

        $jwt = trim(substr($authorizationHeader, 7));
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            if ($decoded->iss != $_SERVER['HTTP_HOST']) {
                throw new ClientErrorException("invalid token issuer", 401);
              }
        } catch (Exception $e) {
            throw new ClientErrorException($e->getMessage(), 401);
        }
    }

}