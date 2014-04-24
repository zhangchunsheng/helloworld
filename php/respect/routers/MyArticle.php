<?php
use Respect\Rest\Routable;

class MyArticle implements Routable {
	public function get($id) {
		echo "hello myArticle";
	}

	public function delete($id) {

	}

	public function put($id) {

	}
}