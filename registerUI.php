<h3>Register</h3>

<input id="username" placeholder="Username">
<input id="email" placeholder="Email">
<input type="password" id="password" placeholder="Password">

<button onclick="register()">Register</button>

<p id="msg"></p>

<script>
function register(){

fetch("register.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify({
        username: username.value,
        email: email.value,
        password: password.value
    })
})
.then(res => res.json())
.then(data => {
    msg.innerText = data.message;
});

}
</script>