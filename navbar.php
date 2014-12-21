<?php
echo '<div id="navbar">';
echo '<div id="navbar-content">';

	$root = '/'; //define the root of your navbar - / is the vhost root
	$thisPage = $_SERVER['PHP_SELF']; //get the file the navbar is displayed in
	$curDir = dirname($thisPage);
	$path = '';
	$thisPage = basename($thisPage, '.php'); //get only the filename (w/o extension)
	
	if($thisPage != 'index') { //if it is not an index.php file, display it
		$path = ' -> <b>'.$thisPage.'</b>';
	} else if($root != $curDir){ //if it is an index.php file and not in the root dir, display the dir
		$path = ' -> <b>'.basename($curDir).'</b>';
		$curDir = dirname($curDir); //update $curDir
	}
	while($root != $curDir) { //print all parent dirs as links
		$path = ' -> <a href="'.$curDir.'">'.basename($curDir).'</a>'.$path; //and link to current dir
		$curDir = dirname($curDir); //update $curDir
	}
	if(realpath(dirname($thisPage)) != $_SERVER['DOCUMENT_ROOT']) { //if thisPage is not in root, link to root
		$path = '<a href="/">home</a>'.$path;
	} else {
		$path = '<b>home<b>'.$path; //else just say this is the homepage
	}
	echo $path;
	
	if(isset($dontscan)){} else { //param to disable "directory scan". if not set:
		foreach(scandir(dirname($thisPage)) as $file){ //scan dir for non-invisible folders and php files
			if(($file !== 'index.php') && strpos($file, '.') !== 0 && substr($file, -4) === '.php' && basename($file, '.php') !== $thisPage) { //visible php file that is neither index nor thisPage
				echo ' | <a href="'.basename($file).'">'.basename($file, '.php').'</a>'; //write " | name" & link
			} else if(is_dir($file) && strpos($file, '.') !== 0) { //visible folder
				echo ' | <a href="'.basename($file).'">'.basename($file).'</a>'; //write " | name" & link
			}
		}
	}
	
echo '</div>';	
echo '</div>';
?>
