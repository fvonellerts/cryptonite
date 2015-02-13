<html>
<head>
<title>Update Panel</title>
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
    <h1>update cryptonite</h1>
    <p>This panel uses the "download copy" feature of cryptonite in order to grab the target server's cryptonite files and update the local ones.</p>
     <p>Be aware that this can be dangerous since the target server could modify the code. Only use trusted servers!</p>
<?php
error_reporting(0);
session_start();
error_reporting(0);
$creds = file_get_contents("password.txt");
$sess = $_SESSION["admin"];
if (hash('sha512', $sess) == $creds) {
} else {
header('Location: index.php?x=404');
exit(1);
}

if (isset($_POST["send"])) {
$response = file_get_contents($_POST["url"] . '?x=install');
if (preg_match('/Fresh install zip created/',$response)) {
mkdir("tmp");
function downloadFile ($url, $path) {

      $newfname = $path;
      $file = fopen ($url, "rb");
      if ($file) {
        $newf = fopen ($newfname, "wb");

        if ($newf)
        while(!feof($file)) {
          fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
        }
      }

      if ($file) {
        fclose($file);
      }

      if ($newf) {
        fclose($newf);
      }
}
$fileurl = explode("/index.php",$_POST["url"]);
$file = $fileurl[0] . "/cryptonite.zip";
downloadFile($file, "tmp/crypt.zip");
$zip = new ZipArchive;
$res = $zip->open('tmp/crypt.zip');
if ($res === TRUE) {
  $zip->extractTo('tmp/');
  $zip->close();
}
unlink("tmp/crypt.zip");
// unzipped content in tmp
unlink("index.php");
rename('tmp/index.php', 'index.php');
unlink("admin.php");
rename('tmp/admin.php', 'admin.php');
unlink("adduser.php");
rename('tmp/adduser.php', 'adduser.php');
unlink("favicon.ico");
rename('tmp/favicon.ico', 'favicon.ico');
unlink("external.php");
rename('tmp/external.php', 'external.php');
unlink("frame.php");
rename('tmp/frame.php', 'frame.php');
unlink("jquery.min.js");
rename('tmp/jquery.min.js', 'jquery.min.js');
unlink("tut.php");
rename('tmp/tut.php', 'tut.php');
unlink("user.php");
rename('tmp/user.php', 'user.php');
function deleteDir($path) {
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}
deleteDir("tmp");
file_get_contents($_POST["url"] . '?x=clean');
echo "<div class=\"alert alert-success\" role=\"alert\">Zip downloaded and contents installed. <a href=\"update.php\" class=\"alert-link\">X</a></div>";
} else {
echo "<div class=\"alert alert-danger\" role=\"alert\">No zip has been created on target server. Maybe it's offline? <a href=\"update.php\" class=\"alert-link\">X</a></div>";
}
}
?>
<br>
    <form action="" method="post">

<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">URL</span>
  <input type="text" class="form-control" name="url" placeholder="http://mycryptoniteserver.net/index.php" aria-describedby="basic-addon1">
</div>
  
  <br>
  <div class="btn-group" role="group" aria-label="...">
     <a href="admin.php" class="btn btn-default"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> back</a>
    <button type="submit" name="send" class="btn btn-default">query server</button>
    </div>
    
    </form>
    
</body>
</html>