<?php

require_once __DIR__ . "/../service/SupportService.php";

class SupportController
{

    // Create a new support ticket
    function create($req)
    {
        $supportService = new SupportService();

        try {

            $result = $supportService->create($req);

            return [
                "success" => true,
                "message" => "Support ticket created successfully",
                "data" => $result
            ];

        } catch (PDOException $e) {

            error_log("(CONTROLLER) Error creating support ticket: " . $e->getMessage());

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];

        }

        
    }
}