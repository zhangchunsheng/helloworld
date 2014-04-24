<?php
trait Query {
	public function query() {

	}
}

class Luomor_Abstract {
	use Query;

	protected $_module = null;

	public function __construct() {
		$this -> _api = "http://www.luomor.com/";
	}

	public function __call($method, $arguments) {
		var_dump($method);
		var_export($arguments);

		$class = get_called_class();
		var_dump($class);

		print_r(substr($class, strpos($class, "_") + 1));

		$module = $this->_module ?: str_replace("_", "/", substr($class, strpos($class, "_") + 1));

		print_r($module);
		echo "$module.$method";

		$x = explode(".", "$module.$method");
		print_r($x);

		print_r("{$this->_api}{$x[0]}?method={$x[1]}");
	}
}

class Luomor_Food extends Luomor_Abstract {

}

class Base {
	public function sayHello() {
		echo 'Hello ';
	}
}

trait SayWorld {
	public function sayHello() {
		parent::sayHello();
		echo 'World!';
	}
}

class MyHelloWorld extends Base {
	use SayWorld;
}

$o = new MyHelloWorld();
$o -> sayHello();

$food = new Luomor_Food();
$food -> sayHello("hello", "world");