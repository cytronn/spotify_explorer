<?php namespace app\Models;

class Artist
{
	public function __construct($app, $cache)
	{
		$this->app = $app;
		$this->cache = $cache;
	}

	public function getInformations($artist_id)
	{
		$url = 'https://api.spotify.com/v1/artists/'.$artist_id;
		$result = $this->cache->get_data($url, $url); 
		return $result;
	}

	public function getAlbums($artist_id)
	{
		$url = 'https://api.spotify.com/v1/artists/'.$artist_id.'/albums?album_type=album&market=FR';
		$result = $this->cache->get_data($url, $url); 
		return $result;
	}

	public function getTopTrack($artist_id)
	{
		$url = 'https://api.spotify.com/v1/artists/'.$artist_id.'/top-tracks?country=FR';
		$result = $this->cache->get_data($url, $url); 
		return $result;
	}

}