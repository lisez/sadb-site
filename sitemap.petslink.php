<?php

  header('Content-type: application/xml');
  
  $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
  $xmlUrlsetHeader = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
  $xmlUrlsetEnder = '</urlset>'."\n";

  echo $xmlHeader;
  echo $xmlUrlsetHeader;

  for ($i=483; $i >=1 ; $i--) {
    $xmlURL = "<url>\n<loc>https://sadb.lisezdb.com/pets-%s/</loc>\n</url>\n";
    echo sprintf($xmlURL, $i);
  }

  echo $xmlUrlsetEnder;
?>
