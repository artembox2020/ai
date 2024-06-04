<?php

include "ProviderBuilder.php";
include "NeuralWriter.php";

$providerName = $_REQUEST['providerName'];
$providerUrl = $_REQUEST['providerUrl'];
$apiKey = $_REQUEST['api_key'];
$text = $_REQUEST['text'];
$from = $_REQUEST['from'];

echo NeuralWriter::paraphrase($providerName, $providerUrl, $apiKey, $text, $from, $err);