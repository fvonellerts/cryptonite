<?php
error_reporting(0);
session_start();
error_reporting(0);
$creds = file_get_contents("password.txt");
$sess = $_SESSION["admin"];
if (hash('sha512', $sess) == $creds) {
} else if (hash('sha512', $_SESSION["user"]) == file_get_contents("passworduser.txt")) {
} else {
header('Location: index.php?x=404');
exit(1);
}
?>

<html>
    <head>
        <meta http-equiv="refresh" content="5" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style type="text/css">
        p {
            margin: 0.5px;
            color: gray;
            font-family: sans-serif;
            font-size: 13;
        }
        </style>
    </head>
    <body>
        <?php
        // new messages?
        $fp=fopen("chat.txt","r");
        $chat=fread($fp,8192);
        fclose($fp);
        if ($chat != "0")
        {
        $fp=fopen("chatsaved.txt","r");
        $chat2=fread($fp,8192);
        fclose($fp);
        $handle = fopen("chatsaved.txt", "w");
        fwrite($handle, $chat2 . $chat . "\n");
        fclose($handle);
        $handle = fopen("chat.txt", "w");
        fwrite($handle, "0");
        fclose($handle);
        }
        
        $fp2=fopen("sent.txt","r");
        $chat2=fread($fp2,8192);
        fclose($fp2);
        if ($chat2 != "0")
        {
        echo "<p>report > message sent</p>\n";
        $handle = fopen("sent.txt", "w");
        fwrite($handle, "0");
        fclose($handle);
        }
        
        $fp=fopen("chatsaved.txt","r");
        $chat=fread($fp,8192);
        fclose($fp);
        if (!empty($chat))
        {
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $chat) as $line){
        // reset $chat
        $chat = "";
        if ($line != "") {
        $save = base64_decode($line);
        $save2 = explode("((PHP))", $save);
        $message = $save2[0];
        if (preg_match('/()private()/',$message) && !empty($_SESSION["user"])) {
        echo "<p>" . $save2[1] . " > (private message)</p>\n";
        } else {
        $message = str_replace("()private()", "", $message);
        $fp=fopen("cert.key","r");
        $priv_key=fread($fp,8192);
        fclose($fp);
        $res = openssl_get_privatekey($priv_key);
        $chat = "";
        openssl_private_decrypt($message,$clear,$res);
        $clear = strip_tags($clear);
        // add smiles
        if ($clear != "") {
        if (preg_match('/icon:/',$clear)) {
        $clearnew = "";
        $exp = explode(" ", $clear);
        foreach ($exp as $word) {
        if (preg_match('/icon:/',$word)) {
            $word = str_replace("icon:", "", $word);
            $clearnew = $clearnew . "<i class=\"fa fa-" . $word . "\"></i>" . " ";
        } else {
            $clearnew = $clearnew . $word . " ";
        }
        }
        $clear = $clearnew . "\r";
        }
        echo "<p>" . $save2[1] . " > " . $clear . "</p>\n";
        }
        }
        }
        }
        } else {
             echo "<p>report > no messages received</p>\n";
        }
        ?>
    </body>
</html>