<?php namespace app\Controllers;

class ArtistController
{
	public function __construct($app)
	{
		$this->app = $app;
	}

	public function getInformations($artist_id, $Artist)
	{
		$artist = $Artist;
		$result = $artist->getInformations($artist_id);

		return $result;
	}

	public function getAlbums($artist_id, $Artist)
	{

		$artist = $Artist;
		$result = $artist->getAlbums($artist_id);
		$result = json_decode($result);

		$response = array();
		for ($i = 0; $i < count($result->items); $i++)
		{
			$response[$i] = new \stdClass();
			$response[$i]->id = $result->items[$i]->id;
			$response[$i]->name = $result->items[$i]->name;
			$response[$i]->image = $result->items[$i]->images[1]->url;
		}

		return $response;
	}

	public function getTopTrack($artist_id, $Artist)
	{
		$artist = $Artist;
		$result = $artist->getTopTrack($artist_id);
		$result = json_decode($result);
		
		$response = new \stdClass();
		$response->name = $result->tracks[0]->name;
		$response->preview_url = $result->tracks[0]->preview_url;
		$response->image = $result->tracks[0]->album->images[0]->url;
		$response->explicit = $result->tracks[0]->explicit;

		return $response;
	}
}