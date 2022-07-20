<?php header('Content-type: application/xml'); echo '<?xml version="1.0" encoding="UTF-8"?>'; ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ( glob('/var/www/archive/*/*.png') as $t ) {
  //$info = json_decode(file_get_contents(dirname($dir) . '/.info'), true);
  echo '<url>' .
       '<loc>https://screenapi.cc' . dirname(str_replace(['/var/www', '.png'], '', $t)) .
            ( date('Y-m-d', filectime($t)) == date('Y-m-d') ? '' : ('?dt=' . date('Y-m-d', filectime($t)))) .
       '</loc>' .
       '<lastmod>' . date('c', filectime($t)) . '</lastmod>' .
       '</url>';
  ?>
<?php } ?>
</urlset>