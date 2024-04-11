<?php

function handleSucess($message) {
    $response = array('Sucess' => $message);
    echo json_encode($response);
    exit;
}

