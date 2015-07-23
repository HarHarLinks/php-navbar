<?php
echo '<div id="navbar" class="bar">';
echo '<nav id="navbar-content" class="bar-content">';
echo '  <ul class="clearfix">';

	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/.resources/menu-order.php')) { require($_SERVER['DOCUMENT_ROOT'].'/.resources/menu-order.php'); $customOrder = True; } //check for custom order for menu
	else { $customOrder = False; } //if n/a, deactivate custom menu order

	$root = '/'; //define the root of your navbar - / is the vhost root
	$thisPage = $_SERVER['PHP_SELF']; //get the file the navbar is displayed in
	$curDir = dirname($thisPage);
	$oldDir = '';
	$path = '';
	$thisPage = basename($thisPage, '.php'); //get only the filename (w/o extension)
	
	if(isset($noMenu)) { $noMenu = True; } else { $noMenu = False; } //param to disable dropdown menu. if not set convert to easier to use bool var by making sure it's set
	
	if(($thisPage != 'index') || ($root != $curDir)){
		if($thisPage != 'index') { //if it is not an index.php file, display it
			$path = '-> <b>'.$thisPage.'</b>';
		} else if($root != $curDir){ //we now know it is an index.php file. if it is not in the root dir, display the dir
			$path = '-> <b>'.basename($curDir).'</b>';
			$oldDir = $curDir;
			$curDir = dirname($curDir); //update $curDir to it's parent dir
		}
		if(!$noMenu) { //if we want a menu
			$path = ' <li>'.$path.'</li>'; //we need every path element to be an unordered list item in our 'clearfix' parent list
		} else {
			$path = ' '.$path; //else we just need a leading whitespace
		}
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	while($root != $curDir) { //print all parent dirs as links
		$pathPiece = '-> <a href="'.$curDir.'">'.basename($curDir).'</a>'; //and link to corresponding dir
		if(!$noMenu) { //again, if we want a menu
			$pathPiece = ' <li>'.$pathPiece; //we need every path element to be an unordered list item in our 'clearfix' parent list
			$allSiblings = scandir('../../'.$curDir); //to actually generate we at first need to get all the relevant items
			$siblings = array();
				foreach($allSiblings as $sibling){ //the current element always is a folder or index.php to be precise, so we are only interested in .php files (but index) and subfolders
				if(((is_dir('../'.$sibling) && ($sibling !== basename($oldDir)))) && stripos($sibling, '.') !== 0) {
					array_push($siblings, $sibling); //sort out the siblings that are dirs, but not the current or non-index.php files and also not invisible
				}
			}
			if(count($siblings) >= 1) { //if there are corresponding siblings, we generate a sublist containing those siblings
				$pathPiece = $pathPiece.'<div class="sub-menu"><ul>';
				if(/*$customOrder*/true && isset($index[basename($curDir)])) {
					foreach($index[basename($curDir)] as $entry => $ind){ //search all indices
						if(in_array($entry, $siblings)){ //! TODO: doesn't actually use indices, but only order in array
							if($ind >= 0){ //add all elements with nonnegative indices
								$pathPiece = $pathPiece.'<li>\'-> <a href="'.$curDir.'/'.$entry.'">'.$entry.'</a></li>';
							}
							unset($siblings[array_search($entry, $siblings)]); //delete to prevent double appearance
						}
					}
				}
				foreach($siblings as $sibling){ //add the rest alphabetically
					$pathPiece = $pathPiece.'<li>\'-> <a href="'.$curDir.'/'.$sibling.'">'.$sibling.'</a></li>';
				}
				$pathPiece = $pathPiece.'</ul></div></li>';
				$path = $pathPiece.$path;
			}
		} else {
			$path = ' '.$pathPiece.$path; //else we just need a leading whitespace.
		}
		$oldDir = $curDir;
		$curDir = dirname($curDir); //finally update $curDir to it's parent dir to recurse
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//if(realpath(dirname($thisPage)) != $_SERVER['DOCUMENT_ROOT']) { //if thisPage is not in root, link to root
		$pathPiece = '<a href="/">home</a>';
	//} else {
	//	$pathPiece = '<b>home</b>'; //else just say this is the homepage
	//}
	if(!$noMenu) { //again, if we want a menu
		$pathPiece = ' <li>'.$pathPiece; //we need every path element to be an unordered list item in our 'clearfix' parent list
		$curDir = $_SERVER['DOCUMENT_ROOT'];
		$allSiblings = scandir($curDir); //to actually generate we at first need to get all the relevant items
		$siblings = array();
			foreach($allSiblings as $sibling){ //the current element always is a folder or index.php to be precise, so we are only interested in .php files (but index) and subfolders
			if(((is_dir($curDir.'/'.$sibling) && ($sibling !== basename($oldDir)))) && stripos($sibling, '.') !== 0) {
				array_push($siblings, $sibling); //sort out the siblings that are dirs, but not the current or non-index.php files and also not invisible
			}
		}
		if(count($siblings) >= 1) { //if there are corresponding siblings, we generate a sublist containing those siblings
			$pathPiece = $pathPiece.'<div class="sub-menu"><ul>';
			if($customOrder && isset($index[basename('home')])) {
				foreach($index[basename('home')] as $entry => $ind){ //search all indices
					if(in_array($entry, $siblings)){ //! TODO: doesn't actually use indices, but only order in array
						if($ind >= 0){ //add all elements with nonnegative indices
							$pathPiece = $pathPiece.'<li>\'-> <a href="/'.$entry.'">'.$entry.'</a></li>';
						}
						unset($siblings[array_search($entry, $siblings)]); //delete to prevent double appearance
					}
				}
			}
			foreach($siblings as $sibling){ //add the rest alphabetically
				$pathPiece = $pathPiece.'<li>\'-> <a href="/'.$sibling.'">'.$sibling.'</a></li>';
			}
			$pathPiece = $pathPiece.'</ul></div></li>';
			$path = $pathPiece.$path;
		}
	} else {
		$path = ' '.$pathPiece.$path; //else we just need a leading whitespace.
	}
	echo $path;
	
	if(isset($dontScan)) {} else { //param to disable "directory scan". if not set:
		foreach(scandir(dirname($thisPage)) as $file){ //scan dir for non-invisible folders and php files
			if(($file !== 'index.php') && strpos($file, '.') !== 0 && substr($file, -4) === '.php' && basename($file, '.php') !== $thisPage) { //visible php file that is neither index nor thisPage
				echo ' | <a href="'.basename($file).'">'.basename($file, '.php').'</a>'; //write " | name" & link
			} else if(is_dir($file) && strpos($file, '.') !== 0) { //visible folder
				echo ' | <a href="'.basename($file).'">'.basename($file).'</a>'; //write " | name" & link
			}
		}
	}


echo '  </ul>';	
echo '</nav>';	
echo '</div>';
?>
