<!-- How Telemarketers Will See Their Assigned Lists
When an employee logs in, they will see their assigned target lists with the corresponding accounts and contacts.

You can create a separate page like employee_targetlists.php to show this:

Here’s an example of how your employee_targetlists.php page could look with the "Call" button and a modal for logging call outcomes:
-->

<?php
session_start();
require 'src/config/config.php';
require 'src/Services/TargetListsApi.php';
require 'src/Services/ContactsApi.php';
require 'src/Services/CallLogsApi.php';  // To log calls

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();

$targetListsApi = new TargetListsApi($accessToken, $tokenManager);
$contactsApi = new ContactsApi($accessToken, $tokenManager);
$callLogsApi = new CallLogsApi($accessToken, $tokenManager);  // For logging call outcomes

$employeeId = $_SESSION['employee_id'];  // Assuming employee ID is stored in session after login
$targetLists = $targetListsApi->getTargetListsByEmployee($employeeId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Target Lists</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Your Assigned Target Lists</h1>
        <?php foreach ($targetLists['data'] as $targetList): ?>
            <h2>Target List: <?= $targetList['attributes']['name'] ?></h2>
            <ul>
                <?php foreach ($targetList['related']['accounts'] as $account): ?>
                    <li>
                        <?= $account['attributes']['name'] ?>
                        <!-- Call button opens a modal -->
                        <button class="btn btn-primary" onclick="openCallModal('<?= $account['attributes']['name'] ?>', '<?= $account['id'] ?>')">Call</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </div>

    <!-- Modal for Call Logging -->
    <div class="modal fade" id="callModal" tabindex="-1" role="dialog" aria-labelledby="callModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="callModalLabel">Log Call Outcome</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="callLogForm">
                        <input type="hidden" name="account_id" id="account_id">
                        <div class="form-group">
                            <label for="outcome">Call Outcome:</label>
                            <select class="form-control" name="outcome" id="outcome">
                                <option value="completed">Call Completed</option>
                                <option value="no_answer">No Answer</option>
                                <option value="not_available">Not Available</option>
                                <option value="call_later">Call Later</option>
                                <option value="hung_up">Hung Up</option>
                                <option value="interested">Interested</option>
                                <option value="not_interested">No Thanks</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitCallLog()">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openCallModal(accountName, accountId) {
            $('#callModalLabel').text('Log Call Outcome for ' + accountName);
            $('#account_id').val(accountId);
            $('#callModal').modal('show');
        }

        function submitCallLog() {
            const accountId = $('#account_id').val();
            const outcome = $('#outcome').val();
            const notes = $('#notes').val();

            $.post('log_call.php', {
                account_id: accountId,
                outcome: outcome,
                notes: notes
            }, function(response) {
                alert('Call logged successfully');
                $('#callModal').modal('hide');
            });
        }
    </script>
</body>
</html>
