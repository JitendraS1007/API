<?php

function handleError($message) {
    $response = array('error' => $message);
    echo json_encode($response);
    exit;
}
