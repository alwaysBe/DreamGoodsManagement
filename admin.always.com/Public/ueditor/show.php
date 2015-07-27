<?php
header("Content-type:text/html;charset=utf-8");
$content=isset($_POST['content'])?$_POST['content']:"";
var_dump($content);
require 'show.html';
?>

