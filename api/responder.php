<?php
include "config.php";

$message = "";

/* INSERT RESPONDER */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'] ?? "";
    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";
    $contact = $_POST['contact'] ?? "";

    if(!empty($name) && !empty($email) && !empty($password) && !empty($contact)){

        $password = md5($password);

        $stmt = $conn->prepare("
        INSERT INTO responder
        (resp_name,resp_email,resp_password,resp_contact)
        VALUES (?,?,?,?)
        ");

        $stmt->bind_param("ssss",$name,$email,$password,$contact);
        $stmt->execute();

        $message = "Responder added successfully";
    }else{
        $message = "Please fill all fields";
    }
}

/* GET RESPONDERS */
$stmt = $conn->prepare("
SELECT resp_id,resp_name,resp_email,resp_contact 
FROM responder 
ORDER BY resp_id DESC
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>

<title>Responder Management</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family:Segoe UI;
background:#ffeaea;
color:black;
margin:0;
padding:25px;
}

/* Title */
h2{
text-align:center;
}

/* Container */
.container{
display:flex;
gap:25px;
flex-wrap:wrap;
}

/* Form */
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
color:black;
}

/* Button */
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
padding:14px;
border-bottom:1px solid #eee;
color:black;
}

tr:hover{
background:#fff5f5;
}

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

<h2>❤️ Responder Management</h2>

<?php if($message){ ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>

<div class="container">

<!-- Form -->
<form method="POST">
<h3>Add Responder</h3>

<input type="text" name="name" placeholder="Responder Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<input type="text" name="contact" placeholder="Contact Number" required>

<button type="submit">Add Responder</button>
</form>

<!-- Table -->
<div class="table-wrapper">

<table>

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Contact</th>
</tr>
</thead>

<tbody>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>
<td><?php echo $row['resp_id']; ?></td>
<td><?php echo $row['resp_name']; ?></td>
<td><?php echo $row['resp_email']; ?></td>
<td><?php echo $row['resp_contact']; ?></td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>