<?php
/**
 * Sign-Up Endpoint
 * 
 * @author Ben Kelly w19014367
 */
class Signup extends Endpoint {
    
    public function __construct() {
        try {
            http_response_code(200);

            $db = new Database("db/kickbuddy.sqlite");
            $sql = "INSERT INTO users (userID, playerID, :username, :password)
                    VALUES (50, 58, 'newUser', 'password')";
            $params = [':username'=>$_POST['username'], ':password'=>$_POST['password']];

            $queryResult = $db->executeSQL($sql, $params);

            $this->setData(array(
                "length" => count($queryResult),
                "message" => "Success",
                "data" => $queryResult
            ));

        } catch (PDOException $ex) {
            http_response_code(500); /** 500 - Generic error response (CHANGE) */
            echo($ex."this error is thrown by signup.php catch block");
        }
    }
}