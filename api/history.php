<?php
include("config.php");

$stmt = $conn->prepare("
SELECT 
i.incident_id,
i.status,
i.start_time,
r.resp_name,
p.pat_name
FROM incident i
LEFT JOIN responder r ON i.resp_id = r.resp_id
LEFT JOIN patient p ON i.pat_id = p.pat_id
ORDER BY i.start_time DESC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Incident History</title>

<style>

body{
font-family:Segoe UI;
background:#ffeaea;
color:black;
margin:0;
padding:30px;
}

/* Title */
h2{
text-align:center;
margin-bottom:25px;
}

/* Table Container */
.table-wrapper{
overflow-x:auto;
}

/* Table Style */
table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 0 15px rgba(0,0,0,0.05);
}

th{
background:#ff8c8c;
color:white;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #f1f1f1;
color:black;
}

/* Hover Effect */
tr:hover{
background:#fff5f5;
}

/* Status Badge */
.status{
font-weight:bold;
padding:6px 12px;
border-radius:8px;
display:inline-block;
}

.active{
background:#ef4444;
color:white;
}

.pending{
background:#f59e0b;
color:black;
}

.transferred{
background:#22c55e;
color:white;
}

/* Responsive */
@media(max-width:768px){

body{
padding:15px;
}

th,td{
padding:12px;
font-size:14px;
}

}

</style>

</head>

<body>

<h2> Incident History</h2>

<div class="table-wrapper">

<table>

<thead>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Responder</th>
<th>Status</th>
<th>Start Time</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo $row['incident_id']; ?></td>
<td><?php echo $row['pat_name'] ?? 'N/A'; ?></td>
<td><?php echo $row['resp_name'] ?? 'N/A'; ?></td>

<td>
<span class="status <?php echo $row['status']; ?>">
<?php echo $row['status']; ?>
</span>
</td>

<td><?php echo $row['start_time']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>