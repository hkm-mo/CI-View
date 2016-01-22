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

This library config should be placed in `/application/config/view.php`

## License

CI View is available under the [MIT license](https://github.com/hkm-mo/CI-View/blob/master/LICENSE).