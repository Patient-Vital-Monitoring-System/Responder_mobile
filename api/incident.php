<?php
include "config.php";

$message = "";

/* ---------- INSERT INCIDENT ---------- */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $log_id = $_POST['log_id'];
    $pat_id = $_POST['pat_id'];
    $resp_id = $_POST['resp_id'];
    $status = $_POST['status'];

    if(empty($log_id) || empty($pat_id) || empty($resp_id) || empty($status)){
        $message = "Please fill all fields.";
    }else{

        $stmt = $conn->prepare("
        INSERT INTO incident
        (log_id,pat_id,resp_id,status,start_time)
        VALUES (?,?,?,?,NOW())
        ");

        $stmt->bind_param("iiis",$log_id,$pat_id,$resp_id,$status);
        $stmt->execute();

        $message = "Incident created successfully.";
    }
}

/* ---------- LOAD INCIDENTS ---------- */
$stmt = $conn->prepare("
SELECT 
i.incident_id,
i.status,
i.start_time,
p.pat_name,
r.resp_name
FROM incident i
LEFT JOIN patient p ON i.pat_id=p.pat_id
LEFT JOIN responder r ON i.resp_id=r.resp_id
ORDER BY i.start_time DESC
");

$stmt->execute();
$incidents = $stmt->get_result();

/* ---------- LOAD PATIENTS ---------- */
$patients = $conn->query("SELECT pat_id, pat_name FROM patient");

/* ---------- LOAD RESPONDERS ---------- */
$responders = $conn->query("SELECT resp_id, resp_name FROM responder");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Incident Management</title>

<style>

body{
font-family:Segoe UI;
background:#ffeaea;
color:black;
margin:0;
padding:30px;
}

h2{
text-align:center;
margin-bottom:30px;
}

/* Container */
.container{
display:flex;
gap:30px;
flex-wrap:wrap;
}

/* Form Card */
form{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 0 15px rgba(0,0,0,0.05);
flex:1 1 300px;
border-left:6px solid #ff8c8c;
}

input,select{
width:100%;
padding:12px;
margin-bottom:12px;
border-radius:8px;
border:1px solid #ddd;
background:white;
color:black;
}

button{
width:100%;
padding:12px;
background:#ff8c8c;
border:none;
border-radius:8px;
color:white;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#ff6b6b;
}

/* Table */
.table-wrapper{
flex:2 1 500px;
overflow-x:auto;
}

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

tr:hover{
background:#fff5f5;
}

/* Status */
.active{color:#ef4444;font-weight:bold;}
.pending{color:#f59e0b;font-weight:bold;}
.transferred{color:#22c55e;font-weight:bold;}

.message{
text-align:center;
color:#ff6b6b;
margin-bottom:20px;
}

/* Responsive */
@media(max-width:768px){
.container{
flex-direction:column;
}
}

</style>

</head>

<body>

<h2> Incident Management</h2>

<?php if($message){ ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>

<div class="container">

<!-- FORM -->
<form method="POST">

<h3>Create Incident</h3>

<input type="number" name="log_id" placeholder="Log ID" required>

<select name="pat_id" required>
<option value="">Select Patient</option>
<?php while($p=$patients->fetch_assoc()){ ?>
<option value="<?php echo $p['pat_id']; ?>">
<?php echo $p['pat_name']; ?>
</option>
<?php } ?>
</select>

<select name="resp_id" required>
<option value="">Select Responder</option>
<?php while($r=$responders->fetch_assoc()){ ?>
<option value="<?php echo $r['resp_id']; ?>">
<?php echo $r['resp_name']; ?>
</option>
<?php } ?>
</select>

<select name="status">
<option value="active">Active</option>
<option value="pending">Pending</option>
<option value="transferred">Transferred</option>
</select>

<button type="submit">Create Incident</button>

</form>

<!-- TABLE -->
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

<?php while($row=$incidents->fetch_assoc()){ ?>

<tr>
<td><?php echo $row['incident_id']; ?></td>
<td><?php echo $row['pat_name']; ?></td>
<td><?php echo $row['resp_name']; ?></td>

<td class="<?php echo $row['status']; ?>">
<?php echo $row['status']; ?>
</td>

<td><?php echo $row['start_time']; ?></td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>