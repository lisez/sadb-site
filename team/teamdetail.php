---
layout: compress
php: "true"
comments: true
---
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/lib/inc_teamdetail.php');?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/lib/inc_comments.php');?>
<!DOCTYPE html>
<html>
  {% include head.html %}
  <body>
    <main class="wrapper">
      {% include header.html %}
		<article class="post container" itemscope itemtype="http://schema.org/BlogPosting">
			<div id="page-topper" class="row"></div>
			<div class="row page-help danger">
				<p>本頁內容採用即時上載方式，如有侵害您的權利、違反中華民國法律或者公序良俗等，請立即留言或者來信告知站長，將會為適時適當處理。</p>
				<p>連絡信箱：stoneagedb@gmail.com</p>
			</div>
			<header class="post-header">
				<h1 class="post-title" itemprop="name headline"><?=$pageTitle;?></h1>
			</header>
			<div class="post-content" itemprop="articleBody">
				<?=$pageContent;?>
			</div>
		</article>
			<?=$pageComments;?>
			{% include footer.html %}
    </main>
  </body>
</html>

<script type="text/javascript" src="/lib/js_pets.js?20161110"></script>
<script type="text/javascript" src="/js/savar.js"></script>
<script type="text/javascript" src="/js/appendPetsImgIcon.js"></script>
<script type="text/javascript">
loopSpan();
</script>