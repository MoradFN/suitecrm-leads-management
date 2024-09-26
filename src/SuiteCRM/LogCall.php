<?php
require '../vendor/autoload.php';

use SuiteCRM\LeadsManager;
use SuiteCRM\TokenManager;

$tokenManager = new TokenManager();
$leadsManager = new LeadsManager($tokenManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactId = $_POST['contact_id'];
    $outcome = $_POST['outcome'];

    // Log the call outcome
    $leadsManager->logCallOutcome($contactId, $outcome);

    header('Location: index.php');
}
