<?php
include "config.php";
?>

<!DOCTYPE html>
<html>
<head>

<title>Patient Vitals</title>

<style>

body{
font-family:Segoe UI;
background:#ffe5e5;
color:black;
padding:40px;
}

h2{
margin-bottom:25px;
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:10px;
overflow:hidden;
box-shadow:0 0 20px rgba(0,0,0,.1);
}

th{
background:#ffcccc;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #f1f1f1;
}

tr:hover{
background:#fff0f0;
}

.bad{
color:#ef4444;
font-weight:bold;
}

.good{
color:#22c55e;
font-weight:bold;
}

</style>

</head>

<body>

<h2>Patient Vital Monitoring (LIVE)</h2>

<table>

<thead>
<tr>
<th>Patient</th>
<th>BP</th>
<th>Heart Rate</th>
<th>Oxygen</th>
<th>Recorded</th>
</tr>
</thead>

<tbody id="vitalTable">

</tbody>

</table>

<script>

function loadVitals(){

fetch("vitals_live_api.php")
.then(res=>res.json())
.then(data=>{

let html="";

data.forEach(v=>{

let hrClass = v.heart_rate > 120 ? "bad" : "good";

html += `
<tr>
<td>${v.pat_name}</td>
<td>${v.bp_systolic}/${v.bp_diastolic}</td>
<td class="${hrClass}">${v.heart_rate} BPM</td>
<td>${v.oxygen_level} %</td>
<td>${v.recorded_at}</td>
</tr>
`;

});

document.getElementById("vitalTable").innerHTML = html;

});

}

/* load immediately */
loadVitals();

/* reload every 2 seconds */
setInterval(loadVitals,2000);

</script>

</body>
</html>