<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題5_1, リストにページング処理を加える</title>
<style>
	div#pagenation {
	   position: relative;
	   overflow: hidden;
	}
	div#pagenation ul {
	    position:relative;
	    left:50%;
	    float:left;
	    list-style: none;
	}
	div#pagenation li {
	    position:relative;
	    left:-50%;
	    float:left;
	}
	div#pagenation li a {
	    border:1px solid #CECECE;
	    margin: 0 3px;
	    padding:3px 7px;
	    display: block;
	    text-decoration:none;
	    color: #666666;
	    background: #fff;
	}
	div#pagenation li.active {
	    border:solid 1px #666666;
	    color: #FFFFFF;
	    background: #3399FF;
	    margin: 0 3px;
        padding: 3px 7px;
	}
	div#pagenation li a:hover{
	    border:solid 1px #666666;
	    color: #FFFFFF;
	    background: #3399FF;
	}
</style>
</head>
<body>
<h1>● 課題5_1, リストにページング処理を加える</h1>
<br>
<?php

require_once( 'kadai5_1_pager.php' );

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


$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);


//現在のページ, 総レコード数(ページャーファイルに遷移)
//pager($now, $rows);

//var_dump($rows);

if(isset($_GET["page"]))
{
	//ページリンク押した場合GET値取得、偽の場合1
	$page = $_GET["page"];
	$obj->pager($page, $rows);
}else{
	$page = 1;
	$obj->pager($page, $rows);
}
//全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
if ($obj->total_page < $obj->show_nav) {
	$obj->show_nav = $obj->total_page;
}

//トータルページ数が2以下か、現在のページが総ページより大きい場合表示しない
/*
if ($obj->total_page <= 1 || $obj->total_page < $obj->current_page )
{
	return;
}
*/


//総ページの半分
$obj->show_navh = floor($obj->show_nav / 2);
//現在のページをナビゲーションの中心にする
$loop_start = $obj->current_page - $obj->show_navh;
$loop_end = $obj->current_page + $obj->show_navh;
//現在のページが両端だったら端にくるようにする
if ($loop_start <= 0)
{
	$loop_start  = 1;
	$loop_end = $obj->show_nav;
}
if ($loop_end > $obj->total_page)
{
	$loop_start  = $obj->total_page - $obj->show_nav +1;
	$loop_end =  $obj->total_page;
}


//var_dump($rows,$obj->page_rec);

/*
 * DBのレコード数が表示レコード数を下回っていれば
 * ページリンク表示しない。
 */
if ($rows >= $obj->page_rec) {

?>
	    <div id="pagenation">
	        <ul>
	            <?php
	            //2ページ移行だったら「一番前へ」を表示
	            if ( $obj->current_page > 2)
	            {
	            	echo '<li class="prev"><a href="'. $obj->path .'1">&laquo;</a></li>';
	            }
	            //最初のページ以外だったら「前へ」を表示
	            if ( $obj->current_page > 1)
	            {
	            	echo '<li class="prev"><a href="'. $obj->path . ($obj->current_page-1).'">&lsaquo;</a></li>';
	            }
	            for ($i=$loop_start; $i<=$loop_end; $i++)
				{
	                if ($i > 0 && $obj->total_page >= $i)
					{
	                    if($i == $obj->current_page)
	                    {
							echo '<li class="active">';
							echo $i;
							echo '</li>';
	                    }
	                    else
	                    {
	                    	echo '<li>';
	                    	echo '<a href="'. $obj->path . $i.'">'.$i.'</a>';
	                    	echo '</li>';
	                    }
	                }
	            }
	            //最後のページ以外だったら「次へ」を表示
	            if ( $obj->current_page < $obj->total_page)
	            {
	            	echo '<li class="next"><a href="'. $obj->path . ($obj->current_page+1).'">&rsaquo;</a></li>';
	            }
	            //最後から２ページ前だったら「一番最後へ」を表示
	            if ( $obj->current_page < $obj->total_page - 1)
	            {
	            	echo '<li class="next"><a href="'. $obj->path . $obj->total_page.'">&raquo;</a></li>';
	            }
	            ?>
	        </ul>
	    </div>

<?php


}

$limit=10;
$offset = ($page - 1)*$limit;
$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1 LIMIT {$offset},{$limit}");


print <<<EOM

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



while ($row = mysql_fetch_array($result))
{
	//town_double_zip_code    (1=該当、0=該当せず)
	if ($row['town_double_zip_code'] == 1)
	{
		$row['town_double_zip_code'] = "該当";
	}
	else
	{
		$row['town_double_zip_code'] = "該当せず";
	}

	//town_multi_address      (1=該当、0=該当せず)
	if ($row['town_multi_address'] == 1)
	{
		$row['town_multi_address'] = "該当";
	}
	else
	{
		$row['town_multi_address'] = "該当せず";
	}

	//town_attach_district    (1=該当、0=該当せず)
	if ($row['town_attach_district'] == 1)
	{
		$row['town_attach_district'] = "該当";
	}
	else
	{
		$row['town_attach_district'] = "該当せず";
	}

	//zip_code_multi_town     (1=該当、0=該当せず)
	if ($row['zip_code_multi_town'] == 1)
	{
		$row['zip_code_multi_town'] = "該当";
	}
	else
	{
		$row['zip_code_multi_town'] = "該当せず";
	}

	//update_check            (0=変更なし、1=変更あり、2=廃止(廃止データのみ使用))
	if ($row['update_check'] == 0)
	{
		$row['update_check'] = "変更なし";
	}
	elseif($row['update_check'] == 1)
	{
		$row['update_check'] = "変更あり";
	}
	else
	{
		$row['update_check'] = "廃止(廃止データのみ使用)";
	}

	//update_reason           (0=変更なし、1=市政・区政・町政・分区・政令指定都市施行、2=住居表示の実施、
	//3=区画整理、4=郵便区調整等、5=訂正、6=廃止(廃止データのみ使用))
	if ($row['update_reason'] == 0)
	{
		$row['update_reason'] = "変更なし";
	}
	elseif($row['update_reason'] == 1)
	{
		$row['update_reason'] = "市政・区政・町政・分区・政令指定都市施行";
	}
	elseif($row['update_reason'] == 2)
	{
		$row['update_reason'] = "住居表示の実施";
	}
	elseif($row['update_reason'] == 3)
	{
		$row['update_reason'] = "区画整理";
	}
	elseif($row['update_reason'] == 4)
	{
		$row['update_reason'] = "郵便区調整等";
	}
	elseif($row['update_reason'] == 5)
	{
		$row['update_reason'] = "訂正";
	}
	else
	{
		$row['update_reason'] = "廃止(廃止データのみ使用)";
	}

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
			<td>{$row['town_double_zip_code']}</td>
			<td>{$row['town_multi_address']}</td>
			<td>{$row['town_attach_district']}</td>
			<td>{$row['zip_code_multi_town']}</td>
			<td>{$row['update_check']}</td>
			<td>{$row['update_reason']}</td>
		</tr>

EOM;
//$a =count($row);
	//mb_convert_variables("UTF-8", "SJIS", $row[]);
}


print "</table>";

mysql_close($link);
?>


</body>
</html>
