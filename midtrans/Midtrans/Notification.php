<?php

namespace Midtrans;

class Notification
{
    private $response;

    public function __construct($input_source = "php://input")
    {
        // Read raw post input and parse as JSON
        $raw_notification = json_decode(file_get_contents($input_source), true);

        // Check if the necessary properties are present
        if (isset($raw_notification['transaction_id'])) {
            // Get the status response using the transaction_id
            $status_response = Transaction::status($raw_notification['transaction_id']);
            $this->response = $status_response;
        } else {
            // If transaction_id is not present, set response to null or handle accordingly
            $this->response = null;
        }

        // Add debugging statements
        error_log('Raw Notification: ' . print_r($raw_notification, true));
        error_log('Status Response: ' . print_r($this->response, true));
    }

    public function __get($name)
    {
        // Check if the response is not null and the property exists
        if ($this->response !== null && isset($this->response->$name)) {
            return $this->response->$name;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
