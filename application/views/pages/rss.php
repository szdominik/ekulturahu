<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
  <title>ekultura.hu</title>
  <link>https://ekultura.hu/</link>
  <description>ekultura.hu – online kulturális magazin</description>
  <language>hu-HU</language>
  <?php foreach ($articles as $ac): ?>
  <item>
      <title><?php echo $ac['title']; ?></title>
      <description><?php echo $ac['short_body']; ?></description>
      <link><?php echo $ac['link']; ?></link>
      <guid isPermaLink="true"><?php echo $ac['link']; ?></guid>
      <pubDate><?php echo $ac['pub_time']; ?></pubDate>
      <dc:creator><?php echo $ac['user_name']; ?></dc:creator>
      <category><?php echo $ac['cat_name']?></category>
      <category><?php echo $ac['subcat_name']; ?></category>
    </item>
  <?php endforeach; ?>
  </channel>
</rss>
