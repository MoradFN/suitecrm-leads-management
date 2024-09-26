<!-- index.php -->
<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access environment variables
$clientId = $_ENV['OAUTH2_CLIENT_ID'];
$clientSecret = $_ENV['OAUTH2_CLIENT_SECRET'];
$tokenUrl = $_ENV['OAUTH2_TOKEN_URL'];

use MoradAdmin\SuitecrmLeadsManagement\SuiteCrmApi;

$api = new SuiteCrmApi();
$api->testApi();

echo "Client ID: $clientId\n";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    echo "Autoload file loaded successfully.";
} else {
    echo "Autoload file NOT found.";
}

if (class_exists('MoradAdmin\SuitecrmLeadsManagement\SuiteCrmApi')) {
    echo "Class SuiteCrmApi found!";
} else {
    echo "Class SuiteCrmApi NOT found!";
}
