<?php

if ( $_SERVER['REQUEST_URI'] != '/' ) {
  if ( strpos($_SERVER['REQUEST_URI'], '/archive') === 0 ) {
    return include 'archive.phtml';
  }
  else if ( $_SERVER['REQUEST_URI'] == '/sitemap.xml' ) {
    return include 'sitemap.php';
  }
  else if ( $_SERVER['REQUEST_URI'] == '/how.png' ) {
    header('Content-type: image/png');
    header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 60*60*24*30*12) . " GMT");
    return include 'how.png';
  }
  
  $uri = $_SERVER['REQUEST_URI'];
  $size = [1280, 720];
  if ( preg_match('/^\/([0-9]+x[0-9]+)(\/.+)/', $uri, $m) ) {
    $uri = $m[2];
    $size = explode('x', $m[1]);
  }
  
  $url = 'http:/' . $uri;
  $check = parse_url($url);
  if ( !$check['path'] ) {
    $url = 'http:/' . $uri . '/';
    $check = parse_url($url);
  }
  
  if ( $check['host'] && $check['path'] ) {
    
    $path = '/var/screens/' . md5($url) . '/' . date('Y-m-d') . ':' . implode('x', $size) . '.png';
    
    if ( !is_file($path) ) {
      $r = new Redis(); 
      $r->connect('127.0.0.1', 6379);
      $r->publish('queue', json_encode(['url' => $url, 'size' => $size]));
    }
    
    
    while ( !is_file($path) ) {
      clearstatcache();
      sleep(1);
    }
    
    header('Content-type: image/png');
    readfile($path);
    
    exit;
  }
  else {
    if ( $_GET['width'] && $_GET['height'] && $_GET['url'] ) {
      $url = intval($_GET['width']) . 'x' . intval($_GET['height']) . '/' . str_replace(['http://', 'https://'], '', $_GET['url']);
      header('Location: /' . $url);
      exit;
    }
    
    header('HTTP/1.0 404 Not Found');
    exit;
  }
}
else {
  include 'form.phtml';
}