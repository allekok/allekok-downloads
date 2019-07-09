<?php
/* download allekok.com's stuff */

/* Run */
$functions = [
    'update_image_allekok_images',
    'update_image_sent_by_users',
    'update_text_contributors',
    'update_text_infos_written_by_users',
    'update_sql',
];

foreach(@$argv as $o)
{
    if(in_array($o, $functions))
	$o();
}

/* Functions */
function download ($url)
{
    while(!($content = file_get_contents($url)))
	sleep(1);
    return $content;
}

function remove_dir($path)
{
    $ignore = ['.','..'];
    $files = array_diff(scandir($path), $ignore);
    foreach($files as $f)
    {
	if(is_dir("$path/$f")) 
	    remove_dir("$path/$f");
	else 
	    unlink("$path/$f");
    }
}

/* allekok.com/image/allekok-images */
function update_image_allekok_images ($poet='all')
{
    $path = 'downloads/allekok.com/image/allekok-images/120x120';
    remove_dir($path);
    
    $url = "https://allekok.com/dev/tools/poet.php?poet=$poet";
    while(!($poets = json_decode(download($url), true)))
	sleep(1);

    $images = [];
    foreach($poets as $pt)
    {
	$img = $pt['img']['_130x130'];
	if(in_array($img, $images))
	    continue;
	$images[] = $img;
	$filename = substr($img, strrpos($img, '/'));
	$path = 'downloads/allekok.com/image/allekok-images/120x120'.
		$filename;
	file_put_contents($path, download($img));
	echo "'$path' Updated.\n";
    }
    echo "allekok.com/image/allekok-images -> Done.\n";
}

/* allekok.com/text/contributors */
function update_text_contributors ()
{
    $path = 'downloads/allekok.com/text/contributors/';
    remove_dir($path);
    
    $files = [
	'comments.txt',
	'images.txt',
	'pdfs.txt',
	'poems.txt',
	'poet-descs.txt',
    ];

    foreach ($files as $file)
    {
	$path = 'downloads/allekok.com/text/contributors/'.$file;
	$url = 'https://allekok.com/pitew/contributors/'.$file;
	$content = download($url);
	file_put_contents($path, $content);
	echo "'$path' Updated.\n";
    }
    echo "allekok.com/text/contributors -> Done.\n";
}

/* allekok.com/image/sent-by-users */
function update_image_sent_by_users ()
{
    
}

/* allekok.com/text/infos-written-by-users */
function update_text_infos_written_by_users ()
{
    
}

/* allekok.com/sql */
function update_sql ()
{
    
}
?>
