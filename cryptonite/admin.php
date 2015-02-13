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
?>

<html>
    <head>
<title>Admin Panel</title>
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
        h1 a {
            color:black;
            transition-duration: 0.8s;
        }
        h1 a:hover {
            color: gray;
        }
    </style>
    </head>
    
    <body>
<h1><a href="admin.php?action=changeinterface"><i class="fa fa-diamond"></i></a> cryptonite</h1>
<p>the secure ssl online messager. you are logged in as admin</p>


<?php
//is this a fresh install
if (empty(file_get_contents("cert.key"))) {
    unlink("cert.key");
    echo "<div style=\"margin-top: 20px;\" class=\"alert alert-info\" role=\"alert\">Welcome to cryptonite. Before using the messager, generate a keypair. </div>";
}

if ($_SESSION["back"] == "special") {
    echo "<style>body { background: url(\"http://i.imgur.com/X3JZ4z9.jpg?1\") no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; opacity: 0.9; color: white; } h1 a { transition-duration:0.8s; color:white; } h1 a:hover { color:gray; }</style>";
}

if (isset($_POST["send"]))
{
    
$ip = $_POST["ip"];
setcookie("ipsaved", $ip, time()+60);
$msg = $_POST["message"];
$name = $_POST["name"];
$priv = $_POST["private"];
setcookie("namesaved", $name, time()+60);
$cryptx = $msg;
if (empty($msg) || empty($name) || empty($name)) {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Oh no!</strong> Please enter a target, name and message text.
</div>
<?php
} else {
file_get_contents($ip . '?x=export');
$fileurl = explode("/index.php",$ip);
$file = $fileurl[0] . "/sharekey.public";
$crypt = file_get_contents($file);
file_get_contents($ip . '?x=clean');
openssl_public_encrypt($msg, $cryptx, $crypt);
if ($priv == "1") {
    $cryptx = "()private()" . $cryptx;
}
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
file_put_contents("sent.txt", "1");
}
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'generate':
            generate();
            break;
        case 'generatemanual':
            generatemanual();
            break;
        case 'export':
            exportnow();
            break;
        case 'reset':
            resetnow();
            break;
        case 'update':
            update();
            break;
        case 'resetchat':
            resetchatnow();
            break;
        case 'setuser':
            setuser();
            break;
        case 'changeinterface':
            changeinterface();
            break;
    }
}

function setuser() {
header('Location: adduser.php');
exit(1);
}

function generatemanual()
{
header('Location: external.php');
exit(1);
}

function changeinterface() {
    if ($_SESSION["back"] == "standart") {
    $_SESSION["back"] = "special";
    header('Location: admin.php');
    exit(1);
    } else {
    $_SESSION["back"] = "standart";
    header('Location: admin.php');
    exit(1);
    }
}

function update() {
    header('Location: update.php');
exit(1);
}

function generate()
{
$res = openssl_pkey_new();
openssl_pkey_export($res, $privKey);
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];
file_put_contents('cert.key', $privKey);
file_put_contents('cert.public', $pubKey);
if (!empty(file_get_contents('cert.public'))) {
?>
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Nice!</strong> Keypair successfully generated.
</div>
<?php
} else {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Oh no!</strong> Keypair generation failed.
</div>
<?php
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
} else {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Oh no!</strong> The sharekey does not exist, please generate a keypair first.
</div>
<?php
}

}

function resetnow() {
file_put_contents('password.txt', "");
header('Location: index.php?x=out');
exit(1);
}

function resetchatnow() {
file_put_contents('chatsaved.txt', "");
if (empty(file_get_contents("chatsaved.txt"))) {
echo "<div class=\"alert alert-success\" role=\"alert\">Chat log successfully deleted. <a href=\"admin.php\" class=\"alert-link\">X</a></div>";
} else {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Oh no!</strong> Could not delete chat log! 
</div>
<?php
echo "<div class=\"alert alert-danger\" role=\"alert\">Could not delete chat log! <a href=\"admin.php\" class=\"alert-link\">X</a></div>";
}
}
?>

<div class="btn-group" role="group" aria-label="...">
<div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> generate keypair
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="admin.php?action=generate"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> generate localy</a></li>
      <li><a href="admin.php?action=generatemanual"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span>  or externaly</a></li>
    </ul>
  </div>
  <a href="admin.php?action=export" class="btn btn-default"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> export sharekey</a>
  </div>
  <div class="btn-group" role="group" aria-label="...">
  <a href="admin.php?action=resetchat" class="btn btn-default"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> delete chat log</a>
   <a href="admin.php?action=setuser" class="btn btn-default"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> enable client</a>
  <a href="admin.php?action=reset" class="btn btn-default"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> reset account</a>
  <a href="admin.php?action=update" class="btn btn-default"><span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> update</a>
  </div>
  <div class="btn-group" role="group" aria-label="...">
   <a href="index.php?x=out" class="btn btn-info"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> log out</a>
</div>

<br><br>

<div class="embed-responsive embed-responsive-16by9" style="padding-bottom: 42vh;">
  <iframe class="embed-responsive-item" height="100%" width="100%" style="height: 40vh; border: solid #D5D5D5 1px; border-radius: 4px; background: white;" src="frame.php"></iframe>
</div>

<form action="" enctype="multipart/form-data" method="post">

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> target</span>
  <input type="text" name="ip" class="form-control" placeholder="http://114.63.89.22/index.php" value="<?php echo $_COOKIE["ipsaved"]; ?>" aria-describedby="basic-addon1">
  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> name</span>
  <input type="text" class="form-control" name="name" placeholder="gustav" value="<?php echo $_COOKIE["namesaved"]; ?>" aria-describedby="basic-addon1">
</div>

<br>

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> message</span>
  <input type="text" class="form-control" name="message" placeholder="Hallo, mein Name ist Gustav und ich komme aus Wil icon:smile-o (font awesome)" aria-describedby="basic-addon1">
  </div>
  
  <br>
    <div class="checkbox" style="margin-top: 0px;">
    <label>
      <input name="private" value="1" type="checkbox"> Private message
    </label>
  </div>
    <button type="submit" name="send" class="btn btn-default"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>  send message</button>
    </form>

    </body>
</html>