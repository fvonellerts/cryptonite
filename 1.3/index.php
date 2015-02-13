<html>
<head>
<title>Log In</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<script src="jquery.min.js"></script>
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
    <h1>log in</h1>
<?php
error_reporting(0);
session_start();
error_reporting(0);
if (file_get_contents('password.txt') == "") {
    echo "<div style=\"margin-top: 20px;\" class=\"alert alert-info\" role=\"alert\">This seems to be a fresh installation. Register below. </div>";
}

if ($_GET["x"] == "404") {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Nope!</strong> Please log in to access this site.
</div>
<?php
} else if ($_GET["x"] == "out") {
session_unset();
session_destroy();
    ?>
<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>All right!</strong> Log out successfull.
</div>
<?php
} else if ($_GET["x"] == "export") {
if (!empty(file_get_contents('cert.public'))) {
$fp=fopen("cert.public","r");
$key=fread($fp,8192);
fclose($fp);
file_put_contents('sharekey.public', $key);
$filename="sharekey.public";
header("Content-disposition: attachment;filename=$filename");
readfile($filename);
} else {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Oh no!</strong> Could not generate sharekey.
</div>
<?php
    
}

} else if ($_GET["x"] == "clean") {
    
unlink("cryptonite.zip");
unlink("external.zip");
unlink("sharekey.public");
?>
<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>All right!</strong> External keypair, sharekey and certificate zips cleaned.
</div>
<?php

} else if ($_GET["x"] == "ip") {
    
function getLocalIp()
{ return gethostbyname(trim(`hostname`)); }
echo "<div class=\"alert alert-info\" role=\"alert\">Local IP: " . getLocalIp() . " and remote IP: " . file_get_contents("http://icanhazip.com") . "</div>";


} else if ($_GET["x"] == "install") {
    
$zip = new ZipArchive();
$filename = "cryptonite.zip";

if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
echo "<div class=\"alert alert-danger\" role=\"alert\">No zip could be created. <a href=\"index.php\" class=\"alert-link\">X</a></div>";
return;
}
$zip->addFile("index.php","index.php");
$zip->addFile("admin.php","admin.php");
$zip->addFile("external.php","external.php");
$zip->addFile("adduser.php","adduser.php");
$zip->addFile("user.php","user.php");
$zip->addFile("update.php","update.php");
$zip->addFile("tut.php","tut.php");
$zip->addFile("favicon.ico","favicon.ico");
$zip->addFile("frame.php","frame.php");
$zip->addFile("jquery.min.js","jquery.min.js");
$zip->addFromString("chat.txt", "0");
$zip->addFromString("sent.txt", "0");
$zip->addFromString("chatsaved.txt", "");
$zip->addFromString("password.txt", "");
$zip->addFromString("passworduser.txt", "");
$zip->addFromString(".htaccess", "<FilesMatch \".*\.(txt|key)$\">\nOrder Allow,Deny\nDeny from all\nAllow from 127.0.0.1\n</FilesMatch>");

echo "<div style=\"margin-top: 20px;\" class=\"alert alert-success\" role=\"alert\">Fresh install zip created, place on local server. <a href=\"cryptonite.zip\" target=\"_blank\" class=\"alert-link\">download</a> <a href=\"index.php?x=clean\" class=\"alert-link\">clean</a></div>";
} else if ($_GET["x"] == "externalcert") {
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Fail!</strong> Cryptonite is running on windows.
</div>
<?php
  
} else {
$res = openssl_pkey_new();
openssl_pkey_export($res, $privKey);
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];
mkdir("tmp");
file_put_contents('tmp/cert.key', $privKey);
file_put_contents('tmp/cert.public', $pubKey);
$zip = new ZipArchive();
$filename = "external.zip";

if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Fail!</strong> No zip file could be created.
</div>
<?php
return;
}
$zip->addFile("tmp/cert.key","cert.key");
$zip->addFile("tmp/cert.public","cert.public");
$zip->close();

unlink("tmp/cert.key");
unlink("tmp/cert.public");
rmdir("tmp");
echo "<div style=\"margin-top: 20px;\" class=\"alert alert-success\" role=\"alert\">Certificate zip created. <a href=\"external.zip\" target=\"_blank\" class=\"alert-link\">download</a> <a href=\"index.php?x=clean\" class=\"alert-link\">clean</a></div>";
    
}
}

if (isset($_POST["rec"])) {
    file_put_contents('chat.txt', $_POST["rec"]);
} else if (isset($_POST["send"])) {
$creds = file_get_contents('password.txt');

if ($creds == "") {
    $hashed = hash('sha512', $_POST["account"] . "((PHP))" . $_POST["password"]);
    file_put_contents('password.txt', $hashed);
    ?>
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Nice!</strong> The admin password has been set. Don't share it!
</div>
<?php
} else {
    if (hash('sha512', $_POST["account"] . "((PHP))" . $_POST["password"]) == $creds) {
        $_SESSION["admin"] = $_POST["account"] . "((PHP))" . $_POST["password"];
        header('Location: admin.php');
        exit(1);
    } else if (hash('sha512', $_POST["password"]) == file_get_contents('passworduser.txt')) {
        $_SESSION["user"] = $_POST["password"];
        header('Location: user.php');
        exit(1);
    } else {
    ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Nope!</strong> Invalid account data! This attempt has been logged.
</div>
<?php
    }
}
}
?>
<br>
    <form action="index.php" method="post">

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">account</span>
  <input type="text" name="account" class="form-control" placeholder="gustavbeispiel13, leave blank if using client mode" aria-describedby="basic-addon1">
</div>
<br>
<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">password</span>
  <input type="password" class="form-control" name="password" placeholder="di3s3spasswortistschl3cht" aria-describedby="basic-addon1">
</div>
  
  <br>
  <div class="btn-group" role="group" aria-label="...">
      <a href="tut.php" class="btn btn-default"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> help</a>
      <div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> services
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="index.php?x=install"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> generate install</a></li>
      <li><a href="index.php?x=ip"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> IP info</a></li>
      <li><a href="index.php?x=export"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> download sharekey</a></li>
      <li><a href="index.php?x=externalcert"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> generate external keypair</a></li>
      <li><a href="index.php?x=clean"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> clean zips</a></li>
    </ul>
  </div>
    <button type="submit" name="send" class="btn btn-default"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> log in</button>
    </div>
    
    </form>
    
</body>
</html>