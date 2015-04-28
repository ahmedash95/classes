<?php 

echo '
	<form method="GET">
		<input type="text" name="path">
		<button>Go</button>
	</form> 
		<br>
';

if(!isset($_GET['path']))
	return false;

/* *********** Functions ************* */

function pre($data,$type="print"){
	echo '<pre>';
	$type == 'dump' ? var_dump($data) : print_r($data); 
	echo '</pre>';
}

function getNewImg($image_tag){
	$old_img = $image_tag;
	// if Image Has Tag Return False ;
	preg_match_all('/alt="([^"]+)"/', $image_tag, $tag);
		if(isset($tag[1][0]))
			return false;
	preg_match_all('/src="([^"]+)"/', $image_tag, $src);
	// If ! Image return False
	if(!empty($src[1][0])){
	$src = $src[1][0];
	} else {
		return false;
	}
	$image_name = explode('.',basename($src));
	$image_name = $image_name[0];
	$new_img = str_replace('<img', '<img alt="'.$image_name.'"', $image_tag);
	return array($old_img,$new_img);
}

/* *********** Functions ************* */

$path = $_GET['path'].DIRECTORY_SEPARATOR;

$files = scandir($path);

foreach($files as $file){
	if(!is_file($path.$file)){
		$key = (array_search($file, $files));
		unset($files[$key]);
	}
}

// pre($files);

foreach($files as $file){

$file_content = file_get_contents($path.$file,'w');

preg_match_all('/<img[^>]+>/i',$file_content, $fh);

$fh = (array) $fh;


foreach($fh as $key => $img){
	foreach ($img as $l){
		$img = getNewImg($l);
		$file_content = str_replace($img[0],$img[1], $file_content);
	}
}

	$fp = fopen($path.$file, 'w');
		fwrite($fp, $file_content);
	fclose($fp);
}
