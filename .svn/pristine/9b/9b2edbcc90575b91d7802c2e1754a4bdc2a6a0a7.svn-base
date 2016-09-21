
<html>
<head>
</head>
<body>
<script>
function create_code(){
    document.getElementById('code').src = 'login_code.php?'+Math.random()*10000;
}
</script>

</script>
<form method="post" action="login_action.php">
    <table>
    <tr>
<td>name:</td><td><input type="text" name="login_name" /></td>
</tr>
<tr><td>password:</td><td><input type="password" name="login_pass" /></td></tr>
<tr><td>validate:</td><td><input type="text" name="login_code" />
    <img id="code" src="login_code.php" alt="click to change image" style="cursor: pointer; vertical-align:middle;" onClick="create_code()"/></td></tr>
<tr><td colspan="2">
<input type="submit" name="submit" value="send" />

</td></tr>
</table>
</form>
    </body>
</html>