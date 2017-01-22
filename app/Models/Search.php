<?php namespace app\Models;

class Search
{
	public function __construct($app){
		$this->app = $app;
	}

	public function getArtist($query)
	{

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/search?limit=10&q='.$query.'&type=artist');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}
}