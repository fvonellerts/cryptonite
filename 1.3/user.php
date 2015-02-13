<?php
error_reporting(0);
session_start();
error_reporting(0);
$creds = file_get_contents("passworduser.txt");
$sess = $_SESSION["user"];
if (hash('sha512', $sess) == $creds) {
} else {
header('Location: index.php?x=404');
exit(1);
}
?>

<html>
    <head>
<title>Client Panel</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <style type="text/css">
        html {
            text-align: center;
            margin-left: 15%;
            margin-right: 15%;
        }
    </style>
    </head>
    
    <body>
<h1><i class="fa fa-diamond"></i> cryptonite</h1>
<p>the secure ssl online messager. you are logged in as client</p>


<?php
if (isset($_POST["rec"])) {
    file_put_contents('chat.txt', $_POST["rec"]);
} else if (isset($_POST["send"]))
{
    
$ip = "http://" . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
$ip = "https://" . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
}
$msg = $_POST["message"];
$name = $_POST["name"];
$name = $name . " (client)";
setcookie("namesaved", $name, time()+60);
$cryptx = $msg;
$crypt = file_get_contents("cert.public");
if (empty($crypt)) {
echo "<div class=\"alert alert-danger\" role=\"alert\">No crypt file sent, aborting mission. <a href=\"user.php\" class=\"alert-link\">X</a></div>";
} else {
openssl_public_encrypt($msg, $cryptx, $crypt);
$msg2 = base64_encode($cryptx . "((PHP))" . $name);
$url = $ip;
$data = array('rec' => $msg2);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "<div class=\"alert alert-success\" role=\"alert\">Crypted message successfully sent. Entries stored in 1 min. cookies. <a href=\"user.php\" class=\"alert-link\">X</a></div>";
}
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'export':
            exportnow();
            break;
    }
}

function exportnow()
{
if (!empty(file_get_contents('cert.public'))) {
$fp=fopen("cert.public","r");
$key=fread($fp,8192);
fclose($fp);
file_put_contents('sharekey.public', $key);
$filename="sharekey.public";
header("Content-disposition: attachment;filename=$filename");
readfile($filename);
unlink("sharekey.public");
echo "<div class=\"alert alert-success\" role=\"alert\">Sharekey download started. <a href=\"user.php\" class=\"alert-link\">X</a></div>";
} else {
echo "<div class=\"alert alert-danger\" role=\"alert\">The sharekey does not exist, please generate first. <a href=\"user.php\" class=\"alert-link\">X</a></div>";
}

}

?>


<div class="btn-group" role="group" aria-label="...">
  <a href="user.php?action=export" class="btn btn-default"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> export sharekey</a>
   <a href="index.php?x=out" class="btn btn-default"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> log out</a>
</div>

<br><br>

<div class="embed-responsive embed-responsive-16by9" style="padding-bottom: 42vh;">
  <iframe class="embed-responsive-item" height="100%" width="100%" style="height: 40vh; border: solid #D5D5D5 1px; border-radius: 4px;" src="frame.php"></iframe>
</div>

<form action="" method="post">

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> name</span>
  <input type="text" class="form-control" name="name" placeholder="jakob" value="<?php echo $_COOKIE["namesaved"]; ?>" aria-describedby="basic-addon1">
</div>

<br>

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> message</span>
  <input type="text" class="form-control" name="message" placeholder="Hallo, mein Name ist Jakob und ich komme aus Adliswil" aria-describedby="basic-addon1">
  </div>
  
  <br>
  <div class="btn-group" role="group" aria-label="...">
    <button type="submit" name="send" class="btn btn-default"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>  send message</button>
    </div>
    </form>

    </body>
</html>