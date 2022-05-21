<?php

ini_set('default_socket_timeout', -1);

$r = new Redis(); 
$r->connect('127.0.0.1', 6379);
$r->subscribe(['queue'], function($r, $c, $m) {
  $data = json_decode($m, 1);
  $ts = time();
  echo $data['url'] . '...';
  
  $path = '/var/screens/' . md5($data['url']) . '/' . date('Y-m-d') . ':' . implode('x', $data['size']) . '.png';
  @mkdir(dirname($path), 0777, true);
  
  $size = implode(',', $data['size']);
  exec('google-chrome --headless --screenshot="' . $path . '" -window-size=' . $size . ' "' . $data['url'] . '"');
  # exec('optipng -o3 "' . $path .  '"');
  
  echo ' ' . (time() - $ts) . "s\n";
});