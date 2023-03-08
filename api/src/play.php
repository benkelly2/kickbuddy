<?php
/**
 * Play Endpoint
 * 
 * @author Ben Kelly w19014367
 */
 class Play extends Endpoint {

    public function __construct() {
        try{
            http_response_code(200);

            $db = new Database("db/kickbuddy.sqlite");
            // Query currently selects some match info about all matches
            $sql = "SELECT matches.matchName, matches.KODate, matches.KOTime, matches.venue, matches.matchID FROM matches";
            $params = [];

            $queryResult = $db->executeSQL($sql, $params);
            
            $this->setData(array(
                "length" => count($queryResult),
                "message" => "Success",
                "data" => $queryResult
            ));

        } catch (PDOException $ex) {
            /** 500 - Generic error resposne CHANGE */
            http_response_code(500);
            echo ($ex);
        }        
    }

 }