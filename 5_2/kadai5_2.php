<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題5_2, プルダウンの情報をテーブルから取得</title>
</head>
<body>

<h1>●課題5_2, プルダウンの情報をテーブルから取得</h1>
<br>
<?php
//DB接続
$link = mysql_connect("localhost","root","3212");
mysql_query("SET NAMES utf8",$link);
if (!$link) {
	die("接続できませんでした" .mysql_error());
}
$db = mysql_select_db("test" , $link);
if (!$db) {
	die("データベース接続エラーです。" .mysql_error());
}

print  <<<EOM
		<table border = "1">
			<tr>
				<th>全国地方公共団体コード</th>
				<th>旧郵便番号</th>
				<th>郵便番号</th>
				<th>都道府県名(半角カタカナ)</th>
				<th>市区町村名(半角カタカナ)  </th>
				<th>町域名(半角カタカナ)</th>
				<th>都道府県名(漢字)</th>
				<th>市区町村名(漢字)</th>
				<th>町域名(漢字)</th>
				<th>一町域で複数の郵便番号か</th>
				<th>小字毎に番地が起番されている町域か</th>
				<th>丁目を有する町域名か</th>
				<th>一郵便番号で複数の町域か</th>
				<th>更新確認</th>
				<th>更新理由</th>
			</tr>


EOM;

//$sql = "SELECT * FROM `kadai_matsui_ziplist`";


/*******************************************************
 *     結合して表示
 *
 *
 *
 *
 *********************************************************/

$sql = "SELECT  `public_group_code` ,  `zip_code_old` ,  `zip_code` ,  `prefecture_kana` ,  `city_kana` ,
`town_kana` ,  `prefecture` ,  `city` ,  `town` , `town_double_zip_code` ,  `town_multi_address` ,
`town_attach_district` ,  `zip_code_multi_town` ,  `update_check` ,  `update_reason` ,
`kadai_matsui_ziplist`.`town_double_zip_code` ,  `kadai_matsui_ziplist`.`town_multi_address` ,
 A.`show_content` AS A2 , B.`show_content` AS B2 , C.`show_content` AS C2 , D.`show_content` AS D2 ,
 E.`show_content` AS E2 , F.`show_content` AS F2
FROM  `kadai_matsui_ziplist`
LEFT JOIN  `kadai_matsui_town_code_mst` AS A ON  `kadai_matsui_ziplist`.`town_double_zip_code` = A.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS B ON  `kadai_matsui_ziplist`.`town_multi_address` = B.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS C ON  `kadai_matsui_ziplist`.`town_attach_district` = C.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS D ON  `kadai_matsui_ziplist`.`zip_code_multi_town` = D.`code_key_index`
LEFT JOIN  `kadai_matsui_update_check_code_mst` AS E ON  `kadai_matsui_ziplist`.`update_check` = E.`code_key_index`
LEFT JOIN  `kadai_matsui_update_reason_code_mst` AS F ON  `kadai_matsui_ziplist`.`update_reason` = F.`code_key_index`";


$result = mysql_query("$sql");
while ($row = mysql_fetch_array($result)) {
	//var_dump($row);
	print <<<EOM
		<tr>
			<td>{$row['public_group_code']}</td>
			<td>{$row['zip_code_old']}</td>
			<td>{$row['zip_code']}</td>
			<td>{$row['prefecture_kana']}</td>
			<td>{$row['city_kana']}</td>
			<td>{$row['town_kana']}</td>
			<td>{$row['prefecture']}</td>
			<td>{$row['city']}</td>
			<td>{$row['town']}</td>
			<td>{$row['A2']}</td>
			<td>{$row['B2']}</td>
			<td>{$row['C2']}</td>
			<td>{$row['D2']}</td>
			<td>{$row['E2']}</td>
			<td>{$row['F2']}</td>
		</tr>
EOM;

	//mb_convert_variables("UTF-8", "SJIS", $row[]);

}

print "</table>";

mysql_close($link);
?>


</body>
</html>
