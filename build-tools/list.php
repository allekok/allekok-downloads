<?php
/* Function */
function make_list ($path = ".")
{
    $not = [".", "..", "index.md", ".git", "LICENSE",
	    "README.md", "_config.yml", "build-tools"];
    $files = [];
    $dir = opendir($path);
    
    while(false !== ($e = readdir($dir)))
    {
        if(in_array($e, $not))
	    continue;
        if(is_dir("$path/$e"))
            make_list("$path/$e");
	
        $files[] = "- [$e]($e)";
    }

    /* Back link */
    if($path != ".")
	$back = "- [..](..)\n";
    else
	$back = "";

    /* Save list */
    sort($files);
    $list = implode("\n", $files);
    file_put_contents("$path/index.md",
		      "# Index\n$back$list\n");
}

/* Run */
make_list();
?>
