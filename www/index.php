<?php

require '.'.DIRECTORY_SEPARATOR.'config.php';

$path = false;

//Получаем путь
if (preg_match('/^(.*)(?:$|\?)/U',$_SERVER['REQUEST_URI'], $match)) {
	// remove /..
	$path = preg_replace('/\/\.\.(\/|$)/','/',$match['1']);
}

$currentAudioPath = trim($path);//trim(AUDIO_PATH.$path);
// Удаляем слеш на конце
if (strlen($currentAudioPath) > 0 && $currentAudioPath[strlen($currentAudioPath)-1] == '/') {
	$currentAudioPath[strlen($currentAudioPath)-1] = ' ';	  
	$currentAudioPath = trim($currentAudioPath);
}

// предопределенная константа DIRECTORY_SEPARATOR
// Заменяем слеши в зависимости от системы
if (DIRECTORY_SEPARATOR == '/') {
	$currentAudioPath = str_replace('\\','/',$currentAudioPath);
} elseif (DIRECTORY_SEPARATOR == '\\') {
	$currentAudioPath = str_replace('/','\\',$currentAudioPath);	
}

$htmlBack = '';
$htmlFile = '';
$htmlDir = '';

if ($currentAudioPath != '') {
	$htmlBack = '<div class="dir"><p><a href="../"><< Назад</a></p></div>';	
}

$currentAudioPathWeb = AUDIO_PATH_WEB.$currentAudioPath;
$currentAudioPath = AUDIO_PATH.$currentAudioPath;

$dirArray = array();
$fileArray = array();
$musicArray = array();

if ($currentAudioPath !== false) {
	if (is_dir($currentAudioPath)) {
		if ($dh = opendir($currentAudioPath)) {
			while (($file = readdir($dh)) !== false) {
				if ($file != '.' && $file != '..') {					
					if (is_dir($currentAudioPath.DIRECTORY_SEPARATOR.$file)) {
						$dirArray[$file] = array(
							'title' => $file,
							'path' => $file.'/',
						);	 						 						
					} else {
						if (preg_match('/(\.mp3)$/i',$file)) {
							$pathinfo = pathinfo($file);							
							$musicArray[$pathinfo['filename']] = array(
								'title' => $pathinfo['filename'],
								$pathinfo['extension'] => $currentAudioPathWeb.'/'.$file,
							);
						}
						$fileArray[$file] = array(
							'title' => $file,
							'path' => $currentAudioPathWeb.'/'.$file,
						);	
					}
				}	
			}
			closedir($dh);
		}
	}
}

rsort($dirArray);
sort($musicArray);
sort($fileArray);

foreach ($dirArray as $value) 
	$htmlDir .= '<div class="dir"><a href="'.$value['path'].'">'.$value['title'].'</a></div>';

foreach ($fileArray as $value)
	$htmlFile .= '<div class="file"><a href="'.$value['path'].'">'.$value['title'].'</a></div>';

$jPlayerCssSelector = array(
	'jPlayer' => "#jquery_jplayer_1",
	'cssSelectorAncestor' => "#jp_container_1"
	);
$jPlayerPlaylist = $musicArray;
$jPlayerOptions = array(	
	'swfPath' => '/js/jQuery.jPlayer.2.1.0',
	'supplied' => 'mp3',
	'wmode' =>  'window',
);

$jPlayerCssSelector = json_encode($jPlayerCssSelector);
$jPlayerPlaylist = json_encode($jPlayerPlaylist);
$jPlayerOptions = json_encode($jPlayerOptions);

require TEMPLATE_PATH.'/index.tpl.inc';

echo $html;

