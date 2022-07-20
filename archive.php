<?php

$websites = [
  'cnn.com', 'bbc.com', 'pravda.com.ua', 'lenta.ru', 'vesti.ru', 'nytimes.com',
  'buzzfeednews.com', 'aljazeera.com', 'globalissues.org',
  'cnn.com', 'indiatimes.com', 'cnbc.com',
  'washingtonpost.com', 'reuters.com', 'ndtv.com', 'npr.org', 'dw.com',
  'thesun.co.uk', 'express.co.uk', 'time.com', 'france24.com',
  'vox.com', 'smh.com.au', 'ctvnews.ca', 'news24.com', 'channelnewsasia.com',
  'globalnews.ca', 'rawstory.com', 'brookings.edu',
  'dailytelegraph.com.au', 'ifpnews.com', 'kwttoday.com'
];



foreach ( $websites as $url ) {
  $path = '/var/www/archive/' . md5($url);
  $textpath = $path;
  
  if ( !is_dir($path) ) {
    mkdir($path);
    file_put_contents($path . '/.info', json_encode(['url' => $url]));
  }
  
  $path .= '/' . date('Y_m_d') . '.png';
  $textpath .= '/' . date('Y_m_d') . '.txt';

  exec('google-chrome --headless --user-agen="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36"'.
      ' --user-data-dir=/var/www/.config/google-chrome --screenshot="' .
      $path . '" -window-size=' . '1200x800' . ' "https://' . $url . '/" 2>/dev/null');

  echo '.';
  
  sleep(5);
  
  $html = shell_exec('google-chrome --headless --user-agen="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36"'.
      ' --user-data-dir=/var/www/.config/google-chrome --dump-dom "https://' . $url . '/" 2>/dev/null');
  
  $html = trim($html);
  $html = str_replace('><', '> <', $html);
  $html = preg_replace('/(<p[^>]*>)/misu', "\n" . '$1', $html);
  $html = preg_replace('/(<br[^>]*>)/misu', "\n" . '$1', $html);
  $html = preg_replace('/<script.+?\/script>/misu', '', $html);
  $html = preg_replace('/<style.+?\/style>/misu', '', $html);
  if ( preg_match('/(<body.+?body>)/misu', $html, $m) ) {
    $text = trim(strip_tags($m[1]));
    $text = preg_replace('/\n\s+\n/misu', "\n", $text);

    if ( $text ) {
      file_put_contents($textpath, $text);
      echo '+';
    }
    else {
      echo '-';
    }
  }
}