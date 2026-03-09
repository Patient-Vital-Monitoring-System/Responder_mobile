<?php
include "config.php";

$message = "";

/* INSERT PATIENT */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'] ?? "";
    $birthdate = $_POST['birthdate'] ?? "";
    $contact = $_POST['contact'] ?? "";

    if(empty($name) || empty($birthdate) || empty($contact)){
        $message = "Please fill all fields.";
    }else{

        $stmt = $conn->prepare("
        INSERT INTO patient
        (pat_name,birthdate,contact_number)
        VALUES (?,?,?)
        ");

        $stmt->bind_param("sss",$name,$birthdate,$contact);
        $stmt->execute();

        $message = "Patient added successfully.";
    }
}

/* GET PATIENT LIST */
$stmt = $conn->prepare("SELECT * FROM patient ORDER BY pat_id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Management</title>

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
margin-bottom:25px;
}

/* Layout */
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

input{
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
}

tr:hover{
background:#fff5f5;
}

/* Message */
.message{
text-align:center;
margin-bottom:20px;
color:#ff6b6b;
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

<h2>❤️ Patient Management</h2>

<?php if(!empty($message)){ ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>

<div class="container">

<!-- Patient Form -->
<form method="POST">

<h3>Add Patient</h3>

<input type="text" name="name" placeholder="Patient Name" required>

<input type="date" name="birthdate" required>

<input type="text" name="contact" placeholder="Contact Number" required>

<button type="submit">Add Patient</button>

</form>

<!-- Patient Table -->
<div class="table-wrapper">

<table>

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Birthdate</th>
<th>Contact</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?php echo $row['pat_id']; ?></td>
<td><?php echo $row['pat_name']; ?></td>
<td><?php echo $row['birthdate']; ?></td>
<td><?php echo $row['contact_number']; ?></td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>