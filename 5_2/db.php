<?php

//DB接続
$link = mysql_connect("localhost","root","3212");
mysql_query("SET NAMES utf8",$link);
if (!$link)
{
	die("接続できませんでした" .mysql_error());
}
$db = mysql_select_db("test" , $link);
if (!$db)
{
	die("データベース接続エラーです。" .mysql_error());
}


?>

