<?php 

if (!isset($html))
	$html = '';

$html .= <<<HTML
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>audioPlayer</title>    
    <script src="/js/audiojs/audio.min.js"></script>
    <link rel="stylesheet" href="/style/style.css" media="screen">   
    <script>
	  audiojs.events.ready(function() {
	    var as = audiojs.createAll();
	  });
</script>
  </head>
  <body>
HTML;
$html .= $html_body;
$html .= <<<HTML
  </body>
</html>
HTML;
