# CI View

CI View is an advanced view library for [Codeigniter](http://codeigniter.com/). It was influenced by ASP.NET MVC. It backfill the empty of view support in Codeigniter.

It support nested layout/template, menus, JS/CSS files mamagment.

## Installation

1. Download and install Codeigniter
2. Download and extract CI View 
3. Copy `/application/libraries/View` folder to your application libraries folder
4. Copy `/application/config/view.php` to your application config folder
5. Done!

## Getting Started

I suggest you create a folder `_shared` in your application view folder for sharing resources, e.g.: layout. By default, CI View will try to get a view file at `[controller-class]/[controller-method].php` in your view folder.

![Suggested view folder structure](/images/view-folder.png)


Controller1.php
```php
<?php
class Controller1 extends CI_Controller {
	public function index()
	{
		$this->load->library('view');
		$this->view->render();
	}
}
```

index.php
```php
<?php
$this->set_layout('_shared/_layout1');
?>
<h1>Index view</h1>
```

_layout1.php
```php
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->get_title(); ?></title>
	</head>
	<body>
		<h1>Layout 1</h1>
		<?php $this->place_body(); ?>
	</body>
</html>
```


## Config

This library config should be placed in `/application/config/view.php`.

####$config['magic_replace'] = array();

An associative array, replace all occurred keys with values in view output.

####$config['js_debug'] = TRUE;

A config to control if it be switched to minified JS version.

####$config['css_debug'] = TRUE;

A config to control if it be switched to minified CSS version.

####$config['minify_js_ext'] = '.min.js';

A suffix of minified JS file.

####$config['minify_css_ext'] = '.min.css';

A suffix of minified CSS file.

####$config['output_collapse'] = FALSE;

To enable feature, remove all `\n` and `\t` in view output.

####$config['title_separator'] = ' - ';

Join all title semgments wiht title separator.

####$config['base_path_symbol'] = '~/';

Replace all occurred symbols in view output with CodeIgniter base url.

Set it to NULL to disable this feature.

## License

CI View is available under the [MIT license](https://github.com/hkm-mo/CI-View/blob/master/LICENSE).