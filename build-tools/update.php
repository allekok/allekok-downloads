<?php
/* download 'allekok.com' stuff */

/* Run */
$functions = [
    'update_image',
    'update_image_allekok_images',
    'update_image_sent_by_users',
    'update_text',
    'update_text_QAs',
    'update_text_comments',
    'update_text_contributors',
    'update_text_infos_written_by_users',
    'update_sql',
];
if(@$argv[1] == 'all')
{
    update_text();
    update_image();
    update_sql();
}
else
{
    foreach(@$argv as $o)
    {
	if(in_array($o, $functions)) $o();
    }
}

/* Functions */
function download ($url, $timeout=1000)
{
    while(!($content = file_get_contents($url)))
    {
	if(!$timeout--) return "";
	sleep(1);
    }
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
    $path = 'downloads/allekok.com/image/allekok-images/profile';
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
	$path = 'downloads/allekok.com/image/allekok-images/profile'.
		$filename;
	file_put_contents($path, download($img));
	echo "'$path' Updated.\n";
    }
    echo "allekok.com/image/allekok-images -> Done.\n";
}

/* allekok.com/text/contributors */
function update_text_contributors ()
{
    $url = 'https://allekok.com/pitew/contributors/';
    $path = 'downloads/allekok.com/text/contributors/';
    remove_dir($path);
    
    $files = [
	'comments.txt',
	'images.txt',
	'pdfs.txt',
	'poems.txt',
	'poet-descs.txt',
    ];

    foreach ($files as $o)
    {
	file_put_contents($path.$o, download($url.$o));
	echo "'$path$o' Updated.\n";
    }
    echo "allekok.com/text/contributors -> Done.\n";
}

/* allekok.com/image/sent-by-users */
function update_image_sent_by_users ()
{
    $url = 'https://allekok.com/style/img/poets/new/';
    $list = explode("\n", download($url.'list.txt'));
    
    $path = 'downloads/allekok.com/image/sent-by-users/';
    remove_dir($path);
    
    foreach ($list as $o)
    {
	$o = trim($o);
	if(! $o) continue;
	file_put_contents($path.$o,
			  download($url.str_replace(' ', '%20', $o)));
	echo "'$path$o' Updated.\n";
    }
    echo "allekok.com/image/sent-by-users -> Done.\n";
}

/* allekok.com/text/infos-written-by-users */
function update_text_infos_written_by_users ()
{
    $url = 'https://allekok.com/pitew/res/';
    $list = explode("\n", download($url.'list.txt'));
    
    $path = 'downloads/allekok.com/text/infos-written-by-users/';
    remove_dir($path);
    
    foreach ($list as $o)
    {
	$o = trim($o);
	if(! $o) continue;
	file_put_contents($path.$o,
			  download($url.str_replace(' ', '%20', $o)));
	echo "'$path$o' Updated.\n";
    }
    echo "allekok.com/text/infos-written-by-users -> Done.\n";
}

/* allekok.com/text/comments */
function update_text_comments ()
{
    $url = "https://allekok.com/about/comments.txt";
    $path = "downloads/allekok.com/text/comments.txt";
    file_put_contents($path, download($url));
    echo "allekok.com/text/comments.txt -> Done.\n";
}

/* allekok.com/text/QAs */
function update_text_QAs ()
{
    $urls = [
	"https://allekok.com/desktop/QA.txt",
	"https://allekok.com/dev/tools/QA.txt",
	"https://allekok.com/dev/tools/CONTRIBUTING/QA.txt",
	"https://allekok.com/manual/QA.txt",
	"https://allekok.com/pitew/QA.txt",
    ];
    $paths = [
	"downloads/allekok.com/text/QAs/desktop.txt",
	"downloads/allekok.com/text/QAs/dev-tools.txt",
	"downloads/allekok.com/text/QAs/dev-tools-contributing.txt",
	"downloads/allekok.com/text/QAs/manual.txt",
	"downloads/allekok.com/text/QAs/pitew.txt",
    ];
    foreach($urls as $i => $url)
    {
	file_put_contents($paths[$i], download($url, 5));
	echo "'{$paths[$i]}' Updated.\n";
    }
    echo "allekok.com/text/QAs -> Done.\n";
}

/* allekok.com/sql */
function update_sql ()
{
    
}

function update_image ()
{
    update_image_allekok_images();
    update_image_sent_by_users();
}

function update_text ()
{
    update_text_QAs();
    update_text_comments();
    update_text_contributors();
    update_text_infos_written_by_users();
}
?>
