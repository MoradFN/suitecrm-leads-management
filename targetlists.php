<!-- In your targetlists.php, you could create a form that allows an admin to:

Create a new target list.
Add accounts to the target list.
Assign the target list to employees. -->



<?php
session_start();
require 'src/config/config.php';
require 'src/Services/TargetListsApi.php';
require 'src/Services/AccountsApi.php';
require 'src/Services/EmployeesApi.php';  // Assume this handles employee data

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();

$targetListsApi = new TargetListsApi($accessToken, $tokenManager);
$accountsApi = new AccountsApi($accessToken, $tokenManager);
$employeesApi = new EmployeesApi($accessToken, $tokenManager);  // Handles employees

// Handle form submission to create a target list and assign accounts
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetListName = $_POST['target_list_name'];
    $employeeId = $_POST['employee_id'];

    // Create target list
    $newTargetList = $targetListsApi->createTargetList($targetListName);
    $targetListId = $newTargetList['data']['id'];

    // Add accounts to the target list
    foreach ($_POST['accounts'] as $accountId) {
        $targetListsApi->addAccountToTargetList($targetListId, $accountId);
    }

    // Assign target list to an employee
    $targetListsApi->assignTargetList($targetListId, $employeeId);

    echo "Target list assigned successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Target Lists</title>
</head>
<body>
    <h1>Create and Assign Target List</h1>
    <form method="POST" action="targetlists.php">
        <label for="target_list_name">Target List Name:</label>
        <input type="text" id="target_list_name" name="target_list_name" required><br>

        <label for="employee_id">Assign to Employee:</label>
        <select id="employee_id" name="employee_id">
            <?php
            $employees = $employeesApi->getAllEmployees();
            foreach ($employees['data'] as $employee) {
                echo "<option value='{$employee['id']}'>{$employee['attributes']['name']}</option>";
            }
            ?>
        </select><br>

        <h2>Select Accounts to Add:</h2>
        <?php
        $accounts = $accountsApi->getAllAccounts();
        foreach ($accounts['data'] as $account) {
            echo "<input type='checkbox' name='accounts[]' value='{$account['id']}'> {$account['attributes']['name']}<br>";
        }
        ?>

        <button type="submit">Create and Assign Target List</button>
    </form>
</body>
</html>
