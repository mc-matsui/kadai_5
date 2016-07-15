<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題5_2, プルダウンの情報をテーブルから取得</title>
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
	div#pagenation li a:hover {
	    border:solid 1px #666666;
	    color: #FFFFFF;
	    background: #3399FF;
	}
</style>
</head>
<body>
<h1>● 課題5_2, プルダウンの情報をテーブルから取得</h1>
<br>
<?php
require_once( 'db.php' );
require_once( 'kadai5_2_pager.php' );


$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);



if(isset($_GET["page"]))
{
	//ページリンク押した場合GET値取得、偽の場合1
	$page = $_GET["page"];
	$obj->pager($page, $rows);
}
else
{
	$page = 1;
	$obj->pager($page, $rows);
}
//全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
if ($obj->total_page < $obj->show_nav)
{
	$obj->show_nav = $obj->total_page;
}


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
if ($rows >= $obj->page_rec)
{

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
//表示件数
$limit=10;
//ページ-1×表示件数（何ページ目かを設定）
$offset = ($page - 1)*$limit;

/********************************************************************************************************************
 * テーブル結合
 * town_double_zip_code(一町域で複数の郵便番号か)→town_double_T(名前変テーブル)のtown_double_K(名前変カラム)
 * town_multi_address(小字毎に番地が起番されている町域か)→town_multi_T(名前変テーブル)のtown_multi_K(名前変カラム)
 * town_attach_district(丁目を有する町域名か)→town_attach_T(名前変テーブル)のtown_attach_K(名前変カラム)
 * zip_code_multi_town(一郵便番号で複数の町域か)→zip_code_multi_T(名前変テーブル)のzip_code_multi_K(名前変カラム)
 * update_check(更新確認)→update_check_T(名前変テーブル)のupdate_check_K(名前変カラム)
 * update_reason(更新理由)→update_reason_T(名前変テーブル)のupdate_reason_K(名前変カラム)
 *********************************************************************************************************************/

$sql = "SELECT  `public_group_code` ,  `zip_code_old` ,  `zip_code` ,  `prefecture_kana` ,  `city_kana` ,
`town_kana` ,  `prefecture` ,  `city` ,  `town` , `town_double_zip_code` ,  `town_multi_address` ,
`town_attach_district` ,  `zip_code_multi_town` ,  `update_check` ,  `update_reason` ,
`kadai_matsui_ziplist`.`town_double_zip_code` ,  `kadai_matsui_ziplist`.`town_multi_address` ,
 town_double_T.`show_content` AS town_double_K , town_multi_T.`show_content` AS town_multi_K ,
 town_attach_T.`show_content` AS town_attach_K , zip_code_multi_T.`show_content` AS zip_code_multi_K ,
 update_check_T.`show_content` AS update_check_K , update_reason_T.`show_content` AS update_reason_K
FROM  `kadai_matsui_ziplist`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_double_T ON
`kadai_matsui_ziplist`.`town_double_zip_code` = town_double_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_multi_T ON
`kadai_matsui_ziplist`.`town_multi_address` = town_multi_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_attach_T ON
`kadai_matsui_ziplist`.`town_attach_district` = town_attach_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS zip_code_multi_T ON
`kadai_matsui_ziplist`.`zip_code_multi_town` = zip_code_multi_T.`code_key_index`
LEFT JOIN  `kadai_matsui_update_check_code_mst` AS update_check_T ON
`kadai_matsui_ziplist`.`update_check` = update_check_T.`code_key_index`
LEFT JOIN  `kadai_matsui_update_reason_code_mst` AS update_reason_T ON
`kadai_matsui_ziplist`.`update_reason` = update_reason_T.`code_key_index`
LIMIT {$offset},{$limit}";

$result = mysql_query("$sql");


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

//DBデータをループで取得
while ($row = mysql_fetch_array($result))
{
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
			<td>{$row['town_double_K']}</td>
			<td>{$row['town_multi_K']}</td>
			<td>{$row['town_attach_K']}</td>
			<td>{$row['zip_code_multi_K']}</td>
			<td>{$row['update_check_K']}</td>
			<td>{$row['update_reason_K']}</td>
		</tr>

EOM;
}


print "</table>";

mysql_close($link);
?>


</body>
</html>
