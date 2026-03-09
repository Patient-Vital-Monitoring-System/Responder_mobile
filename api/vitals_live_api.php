<?php
include "config.php";

$stmt = $conn->prepare("
SELECT v.*, p.pat_name 
FROM vitalstat v
LEFT JOIN incident i ON v.incident_id=i.incident_id
LEFT JOIN patient p ON i.pat_id=p.pat_id
ORDER BY v.recorded_at DESC
LIMIT 20");

$stmt->execute();
$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);