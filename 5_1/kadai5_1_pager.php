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

