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

$html_body = '';

if ($currentAudioPath != '') {
	$html_body .= '<div class="dir"><p><a href="../"><< Назад</a></p></div>';	
}

$currentAudioPathWeb = AUDIO_PATH_WEB.$currentAudioPath;
$currentAudioPath = AUDIO_PATH.$currentAudioPath;


if ($currentAudioPath !== false) {
	if (is_dir($currentAudioPath)) {
		if ($dh = opendir($currentAudioPath)) {
			while (($file = readdir($dh)) !== false) {
				if ($file != '.' && $file != '..') {					
					if (is_dir($currentAudioPath.DIRECTORY_SEPARATOR.$file)) {
						$html_body .= '<div class="dir"><a href="'.$file.'/">'.$file.'</a></div>';
					} else {
						if (preg_match('/(\.mp3|\.ogg|\.wav)$/i',$file)) 
							$html_body .= '<div class="file"><p>Послушать:<audio src="'.$currentAudioPathWeb.'/'.$file.'" preload="auto" /></p><p>Скачать: <a href="'.$currentAudioPathWeb.'/'.$file.'">'.$file.'</a></p></div>';
					}
					
				}								
				
			}
			closedir($dh);
		}
	}
}

require TEMPLATE_PATH.'/index.tpl.inc';

echo $html;

