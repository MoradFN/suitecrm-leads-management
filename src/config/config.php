<?php
// /src/config
// Set the root directory relative to the config file
$rootPath = dirname(__DIR__, 2); // Moves up two directories from config to root

// Use the root path to load the autoload // Loads all dependencies, including Dotenv, for managing environment variables.
require $rootPath . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file from the 'src/config/' directory
$dotenv = Dotenv::createImmutable($rootPath . '/src/config');
$dotenv->load(); // Parses the '.env' file and loads the variables into `$_ENV`.
