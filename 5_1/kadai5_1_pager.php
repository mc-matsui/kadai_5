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
<?php

class page
{
	public $current_page;
	public $total_rec;
	public $page_rec;
	public $total_page;
	public $show_nav;
	public $path;

	function pager($c, $total)
	{
		$this->current_page = $c;     //現在のページ
		$this->total_rec = $total;    //総レコード数
		$this->page_rec   = 10;   //１ページに表示するレコード
		$this->total_page = ceil($this->total_rec / $this->page_rec); //総ページ数
		$this->show_nav = 5;  //表示するナビゲーションの数
		$this->path = '?page=';   //パーマリンク
	}

}

$obj = new page();


?>

</body>
</html>
