<?php namespace app\Controllers;

class SearchController
{
	public function __construct($app)
	{
		$this->app = $app;
	}

	public function research($query, $Search)
	{
		$request = $Search;
		$result = $request->getArtist($query);
		return $result;
	}
}