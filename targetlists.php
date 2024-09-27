<?php
session_start();
require 'src/config/config.php';
require 'src/Services/TokenManager.php';  
require 'src/Services/ApiClient.php';  
require 'src/Services/AccountsApi.php';  
require 'src/Services/TargetListApi.php';  

if (isset($_POST['distribute'])) {
    $tokenManager = new TokenManager();
    $accessToken = $tokenManager->getAccessToken();  

    $accountsApi = new AccountsApi($accessToken, $tokenManager);
    $targetListApi = new TargetListApi($accessToken);

    // Define pagination variables
    $pageNumber = 1;
    $pageSize = 200;  // Fetch 50 accounts per page to process them in chunks

    // Get all accounts with pagination
    $accounts = $accountsApi->getAllAccounts('name', $pageNumber, $pageSize);

    // Define how many accounts per target list
    $accountsPerTargetList = 200;  
    $accountCount = 0;
    $targetListCount = 1;

    // Create the first target list
    $currentTargetList = $targetListApi->createTargetList("Target List $targetListCount");

    // Loop through accounts and assign them to target lists
    if (isset($accounts['data']) && !empty($accounts['data'])) {
        foreach ($accounts['data'] as $account) {
            $accountId = $account['id'];
            
            // Assign the account to the current target list
            $targetListApi->addAccountToTargetList($currentTargetList['data']['id'], $accountId);

            // Increment account counter
            $accountCount++;

            // Once we've assigned `accountsPerTargetList` accounts, create a new target list
            if ($accountCount >= $accountsPerTargetList) {
                $targetListCount++;
                $currentTargetList = $targetListApi->createTargetList("Target List $targetListCount");
                $accountCount = 0;  // Reset account counter
            }
        }
if (isset($response['error'])) {
    echo "<p>Error: " . htmlspecialchars($response['error']['message']) . "</p>";
}

        echo "Accounts have been successfully distributed over $targetListCount target lists.";
    } else {
        echo "No accounts found to distribute.";
    }
}

?>


<!-- HTML Form to trigger account distribution -->
<form method="POST" action="">
    <button type="submit" name="distribute">Distribute Accounts to Target Lists</button>
</form>
