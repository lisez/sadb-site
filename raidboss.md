---
layout: page
title: "討伐首領"
permalink: /bosstime/
comments: false
---
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/getURLTag.js"></script>
<script type="text/javascript" src="/js/raidboss.js?102"></script>
<script type="text/javascript" src="/lib/js_raidboss.php"></script>
<script type="text/javascript" src="/lib/js_pets.php"></script>
<script type="text/javascript">

$(document).ready(function(){
	addBossTimeList();
	$('.bossSearchClass').click(function(){
		$('#boss-label,#boss-team-setting').empty();
		displayBossInfo($(this).data('bossid'));
	});
});

</script>

## 時間表

目前韓版官方設定是在固定時間，隨機出現首領。出現時間為（以下為UTC+8台灣時間）：

- 14:00 - 15:00
- 21:00 - 22:00

## 出現位置

## 首領資訊

<div id="boss-team">
	<ul id="boss-list" class="nav nav-tabs"></ul>
	<div id="boss-info" style="margin-top: 10px;">
		<div id="boss-label"></div>
		<p>基本上首領戰鬥出場的寵物屬性，<strong>大部分都是雙屬性</strong>，只是目前沒有那麼多圖片，欠缺部分暫時以單屬性圖示代替。</p>
		<div id="boss-team-setting"></div>
	</div>
</div>