<html>
<head>
<title>Enable Client</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<script src="jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <style type="text/css">
        html {
            text-align: center;
            margin-left: 25%;
            margin-right: 25%;
        }
    </style>
    </head>
    
<body>
    <h1>set client password</h1>
    <p>The client account is a possiblity for admins to give users restricted access to their server.</p>
     <p>Users in client mode can only send messages to the connected server and read messages not tagged as private. Be aware that the client mode content is sent uncrypted (if client is not in local network of server), so it should only be used for non-sensitive conversations or only over HTTPS.</p>
<?php
error_reporting(0);
session_start();
$creds = file_get_contents("password.txt");
$sess = $_SESSION["admin"];
if (hash('sha512', $sess) == $creds) {
} else {
header('Location: index.php?x=404');
exit(1);
}

if (isset($_POST["send"])) {
    file_put_contents('passworduser.txt', hash('sha512', $_POST["password"]));
    header('Location: admin.php');
    exit(1);
}
?>
<br>
    <form action="" method="post">

<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">password</span>
  <input type="password" class="form-control" name="password" placeholder="di3s3spasswortistschl3cht" aria-describedby="basic-addon1">
</div>
  
  <br>
  <div class="btn-group" role="group" aria-label="...">
         <a href="admin.php" class="btn btn-default"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> back</a>
    <button type="submit" name="send" class="btn btn-default"> set password</button>
    </div>
    
    </form>
    
</body>
</html>