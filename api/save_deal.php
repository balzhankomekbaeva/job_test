<?php
// api/save_deal.php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

require __DIR__ . '/pd_client.php';
$config = require __DIR__ . '/config.php';

// Form data
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$name = trim($firstName . ' ' . $lastName);
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$zip = trim($_POST['zip'] ?? '');
$area = trim($_POST['area'] ?? '');

$jobType = trim($_POST['job_type'] ?? '');
$jobSource = trim($_POST['job_source'] ?? '');
$jobDesc  = trim($_POST['job_description'] ?? '');

$scheduledDate = trim($_POST['scheduled_date'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');
$endTime = trim($_POST['end_time'] ?? '');
$testSelect = trim($_POST['test_select'] ?? '');

$note = trim($_POST['note'] ?? '');

if ($name === '' || $phone === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Fill in name and phone']);
    exit;
}

// 1) Create Person
$personRes = pd_request('POST', 'persons', [
    'name' => $name,
    'email' => $email,
    'phone' => $phone
]);
if (empty($personRes['success'])) {
    echo json_encode(['success' => false, 'step' => 'create_person', 'response' => $personRes]);
    exit;
}
$personId = $personRes['data']['id'] ?? null;

// 2) Create Deal
$dealPayload = [
    'title' => "Job: $jobType for $name",
    'person_id' => $personId,
    'pipeline_id' => (int)$config['pipeline_id'],
    'stage_id' => (int)$config['stage_id']
];
$dealRes = pd_request('POST', 'deals', $dealPayload);
if (empty($dealRes['success'])) {
    echo json_encode(['success' => false, 'step' => 'create_deal', 'response' => $dealRes]);
    exit;
}
$dealId = $dealRes['data']['id'] ?? null;

// 3) Add note with details
$content = "";
if ($jobSource) $content .= "<p><b>Source:</b> " . htmlspecialchars($jobSource) . "</p>";
if ($jobDesc) $content .= "<p><b>Description:</b> " . nl2br(htmlspecialchars($jobDesc)) . "</p>";
if ($address || $city || $state || $zip || $area) {
    $fullAddr = trim("$address, $city, $state $zip, Area: $area");
    $content .= "<p><b>Service Location:</b> " . htmlspecialchars($fullAddr) . "</p>";
}
if ($scheduledDate) $content .= "<p><b>Scheduled Date:</b> " . htmlspecialchars($scheduledDate) . "</p>";
if ($startTime) $content .= "<p><b>Start Time:</b> " . htmlspecialchars($startTime) . "</p>";
if ($endTime) $content .= "<p><b>End Time:</b> " . htmlspecialchars($endTime) . "</p>";
if ($testSelect) $content .= "<p><b>Test Select:</b> " . htmlspecialchars($testSelect) . "</p>";
if ($note) $content .= "<p><b>Notes:</b> " . nl2br(htmlspecialchars($note)) . "</p>";

if ($content !== '') {
    pd_request('POST', 'notes', [
        'deal_id' => $dealId,
        'content' => $content
    ]);
}

echo json_encode(['success' => true, 'person_id' => $personId, 'deal_id' => $dealId]);
