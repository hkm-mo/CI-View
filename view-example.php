<?php
/*
|--------------------------------------------------------------------------
| JavaScript/CSS debug flag
|--------------------------------------------------------------------------
| 
| To switch JavaScript/CSS files between minified and development version 
| JavaScript/CSS. Before you disable debug flags, you should prepare the 
| minified files. 
| 
*/
$config['js_debug'] = TRUE;
$config['css_debug'] = TRUE;

/*
|--------------------------------------------------------------------------
| Minified JavaScript/CSS files extension
|--------------------------------------------------------------------------
| 
| Replace JavaScript/CSS files extension to minified files 
| extension(suffix).
| 
*/
$config['minify_js_ext'] = '.min.js';
$config['minify_css_ext'] = '.min.css';

/*
|--------------------------------------------------------------------------
| Output collapse
|--------------------------------------------------------------------------
| 
| Remove all tab(\t), new line(\n) and return(\r) characters in output.
| 
| WARNING: It may crash your code. Those characters will be removed without 
| intelligence.
| 
*/
$config['output_collapse'] = FALSE;

/*
|--------------------------------------------------------------------------
| Title Separator
|--------------------------------------------------------------------------
| 
| Separate multiple title segments.
| 
*/
$config['title_separator'] = ' - ';

/*
|--------------------------------------------------------------------------
| Base Path Symbol
|--------------------------------------------------------------------------
| 
| Find and replace all symbol to URL to your CodeIgniter root WITH a 
| trailing slash.
| 
| Set to NULL to disable this feature.
| 
*/
$config['base_path_symbol'] = '~/';

/*
|--------------------------------------------------------------------------
| Magic Replace
|--------------------------------------------------------------------------
| 
| Find key and replace all as its value
| 
*/
$config['magic_replace'] = array();