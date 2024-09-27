<!-- This class will handle the actual logging of call data in SuiteCRM or your database. -->

<?php
// src/Services/CallLogsApi.php
class CallLogsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/CallLogs';

    public function logCall($accountId, $outcome, $notes)
    {
        $data = [
            "data" => [
                "type" => "CallLogs",
                "attributes" => [
                    "account_id" => $accountId,
                    "outcome" => $outcome,
                    "notes" => $notes
                ]
            ]
        ];

        return $this->post($this->baseUrl, $data);
    }
}
