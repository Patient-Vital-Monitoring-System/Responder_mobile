<?php
session_start();
$conn = new mysqli("localhost","root","","vitalwear");

if($conn->connect_error){
    die(json_encode([]));
}

$query = "
SELECT 
p.pat_name,
v.bp_systolic,
v.bp_diastolic,
v.heart_rate,
v.oxygen_level,
v.recorded_at
FROM vitalstat v
JOIN incident i ON v.incident_id = i.incident_id
JOIN patient p ON i.pat_id = p.pat_id
ORDER BY v.recorded_at DESC
LIMIT 5
";

$result = $conn->query($query);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
?>