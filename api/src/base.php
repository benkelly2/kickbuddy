<?php
/**
 * Base endpoitn
 * 
 * @author Ben Kelly w19014367
 */
class Base extends Endpoint {

    public function __construct() {
        try{
            /** Connecting to database */
            $db = new Database("db/kickbuddy.sqlite");

            /** SQL statement to query database */
            $sql = "SELECT username FROM users WHERE userID = 1"; // this is just some test SQL atm
            $params = [];

            /** Store query result into $queryResult variable */ //name of variable can change to be more relevant later
            $queryResult = $db->executeSQL($sql, $params);

            $info = array(
                "appName" => "KickBuddy",
                "author" => "Ben Kelly",
                "studentID" => "w19014367",
                "testData" => $queryResult
            );

            $this->setData(array(
                "length" => count($info),
                "message" => "Success",
                "data" => $info
            ));
        } catch (PDOException $ex) {
            /** 500 - Generic error response */
            http_response_code(500);
            echo ($ex);
        }
        
    }
}