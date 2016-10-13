<?php
$pageComments = <<<EOD
<!-- comments-->
<aside id="comments" class="disqus">

  <div class="container">
    <h3><i class="icon icon-comments-o"></i> 留言回饋</h3>
    <div id="disqus_thread"></div>

    <script type="text/javascript">
      var disqus_shortname = 'sadb-lisezdb';
      var disqus_identifier = '/$pageIdentifier';
      var disqus_title = '$pageTitle';
      var disqus_url = 'https://sadb.lisezdb.com/$pageURL';
      /*var disqus_developer = 1;*/

      (function() {
          var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
      })();
    </script>

    <noscript>
      請啟用 JavaScript，用以瀏覽來自 <a href="https://disqus.com/?ref_noscript" rel="nofollow">Disqus技術支援的留言</a>
    </noscript>
  </div>

</aside>

EOD;

$zipped       = array("\t", "\n", "\r", '  ');
$pageComments = str_replace($zipped, '', $pageComments);
?>