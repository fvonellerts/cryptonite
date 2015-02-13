<html>
<head>
<title>External cert</title>
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
    <h1>generate external certificate</h1>
    <p>The external certificate generation gives PC users the opportunity to let another Cryptonite server running on UNIX generate one for them.</p>
     <p>This is not secure since an attacker could intercept the file. The only fix for this would be a password protection with a crypted transmission, but since PHP lacks support this has been removed.</p>
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
    if (empty($_POST["url"])) {
        echo "<div class=\"alert alert-danger\" role=\"alert\">Please enter the target's index.php URL. <a href=\"external.php\" class=\"alert-link\">X</a></div>";
    } else {
    $response = file_get_contents($_POST["url"] . '?x=externalcert');
if (preg_match('/Certificate zip created/',$response)) {
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
$file = $fileurl[0] . "/external.zip";
downloadFile($file, "tmp/external.zip");
$zip = new ZipArchive;
$res = $zip->open('tmp/external.zip');
if ($res === TRUE) {
  $zip->extractTo('tmp/');
  $zip->close();
}
unlink("cert.key");
rename('tmp/cert.key', 'cert.key');
unlink("cert.public");
rename('tmp/cert.public', 'cert.public');
function deleteDir($path) {
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}
deleteDir("tmp");
file_get_contents($_POST["url"] . '?x=clean');
    echo "<div class=\"alert alert-success\" role=\"alert\">Successfully generated, downloaded and installed certificates. <a href=\"admin.php\" class=\"alert-link\">X</a></div>";
    } else {
    echo "<div class=\"alert alert-danger\" role=\"alert\">Target did not generate anything, maybe it is running on Windows or modified. <a href=\"external.php\" class=\"alert-link\">X</a></div>";
    }
}
}
?>
<br>
    <form action="" method="post">

<div class="input-group">
    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> URL</span>
  <input type="text" class="form-control" name="url" placeholder="http://mylinuxserver.net/index.php" aria-describedby="basic-addon1">
</div>
  
  <br>
  <div class="btn-group" role="group" aria-label="...">
         <a href="admin.php" class="btn btn-default"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> back</a>
    <button type="submit" name="send" class="btn btn-default">query server</button>
    </div>
    
    </form>
    
</body>
</html>