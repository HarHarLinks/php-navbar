php-navbar
==========

This php snipped can generate an overview of where in your web directory the viewed page is located. It can also generate dropdown menus based on that structure and thus be used as navigation bar.

It was developed as navigation bar for the website [sosnowkadub.de](http://sosnowkadub.de) where you can see it implemented and try it out.

usage
------

Save `navbar.php`, `navbar.css` and if required `menu-order.php` somewhere to your web dir. The php files need to be in the same directory to work.   
Load by calling `require('/path/to/navbar.php');` via php from your page, modify and include `navbar.css` by calling `<link rel="stylesheet" href="/path/to/navbar.css" type="text/css">` or similar.

Set the flags `$dontScan = True;` or `$noMenu = True;` beforehand to apply.

For example include `<?php $noMenu = 1; require('/path/to/navbar.css'); ?>` where you want a navbar without dropdown-menu to appear in your php-webpage.

flags
-----

 - `$dontScan = True;` to only show the path but not sibling or child pages.  
 - `$noMenu = True;` to deactivate the dropdown menus
 
menu-order.php
--------------

The `$index` array is used by navbar.php to determine the order in which the items in the dropdown menus appear. A small number equals an early position meaning it will appear at the top of the list, and an item with a great number will appear at the bottom. Use `-1` to hide an item. All items without index will be sorted alphabetically (a-z) and appended after the element with the highest index.

The keys of the primary list correspond to your folder names and contain another (subsequently nested) array containing the names and indices of each subfolder per folder in the primary list. So the primary list keys are the items in the navbar one can hover over and the nested array keys are the items of the dropdown list that would appear, sorted by their indices. Keep in mind this is not an recursing list, but every item can exist in the primary list (although that does not always make sense, eg if it's a file or folder with only 2 children).
