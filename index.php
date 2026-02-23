<!DOCTYPE html>
<html>
<head>
    <title>Vital Wear Login</title>
    <style>
        .status {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .connected {
            color: green;
        }
        .disconnected {
            color: red;
        }
    </style>
</head>
<body>

<h3>Responder Login</h3>

<div id="dbStatus" class="status">Checking database...</div>

<input type="text" id="username" placeholder="Username">
<input type="password" id="password" placeholder="Password">
<button onclick="login()">Login</button>

<p id="message"></p>

<script>


window.onload = function() {
    fetch("connection.php")
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById("dbStatus");

        if (data.status === "success") {
            statusDiv.innerText = data.message;
            statusDiv.classList.add("connected");
        } else {
            statusDiv.innerText = data.message;
            statusDiv.classList.add("disconnected");
        }
    })
    .catch(() => {
        document.getElementById("dbStatus").innerText = "Server Error";
        document.getElementById("dbStatus").classList.add("disconnected");
    });
};

function login() {

    fetch("authentication.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: document.getElementById("username").value,
            password: document.getElementById("password").value
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.status === "success") {
            window.location.href = data.redirect;
        } else {
            document.getElementById("message").innerText = data.message;
        }

    });
}

</script>

</body>
</html>