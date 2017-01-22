<?php namespace app\Models;

class Album
{
	public function __construct($app, $cache)
	{
		$this->app = $app;
		$this->cache = $cache;
	}

	public function getInformations($album_id)
	{
		$url = 'https://api.spotify.com/v1/albums/'.$album_id;
		$result = $this->cache->get_data($url, $url); 
		return $result;
	}

	public function addOne($album_informations, $id)
	{
		$name = $album_informations->name;
		$picture_url = $album_informations->image;
		$id = $id;


		$query = $this->app['db']->query("SELECT * FROM albums WHERE spotify_id = '$id'");
		if(!$query->fetch())
		{
			$prepare = $this->app['db']->prepare('
				INSERT INTO 
					albums (name,picture_url,spotify_id) 
				VALUES 
					(:name,:picture_url,:spotify_id)
			');

			$prepare->bindValue('name', $name);
			$prepare->bindValue('picture_url', $picture_url);
			$prepare->bindValue('spotify_id', $id);

			if ($prepare->execute()) {

			}
		}
	}

	public function addToCollection($album_id, $user_id)
	{
		$album_id = $this->app['db']->query("SELECT ID FROM albums WHERE spotify_id = '$album_id'");
		$album_id = $album_id->fetch();
		$album_id = $album_id->ID;

		$prepare = $this->app['db']->prepare('
			INSERT INTO
				albums_collections (ID_user, ID_album)
			VALUES
				(:id_user,:id_album)
		');

		$prepare->bindValue(':id_user', $user_id);
		$prepare->bindValue(':id_album', $album_id);

		if($prepare->execute())
		{

		}
	}

	public function removeFromCollection($album_id, $user_id)
	{
		$album_id = $this->app['db']->query("SELECT ID FROM albums WHERE spotify_id = '$album_id'");
		$album_id = $album_id->fetch();
		$album_id = $album_id->ID;

		$prepare = $this->app['db']->prepare("
			DELETE FROM
				albums_collections 
			WHERE
				ID_user = '$user_id'
			AND 
				ID_album = '$album_id'
		");


		if($prepare->execute())
		{

		}
	}

	public function inCollection($album_id, $user_id)
	{
		$album_id = $this->app['db']->query("SELECT ID FROM albums WHERE spotify_id = '$album_id'");
		if($album_id->rowCount() == 0)
		{
			$in_collection = 0;
			return $in_collection;
		} else {

			$album_id = $album_id->fetch();
			$album_id = $album_id->ID;
			
			$in_collection = $this->app['db']->query("SELECT * FROM albums_collections WHERE ID_user = '$user_id' AND ID_album = '$album_id'");
			$in_collection = $in_collection->rowCount();
			$in_collection = (int)$in_collection;

			if($in_collection == 0)
			{

				$in_collection = 0;
				return $in_collection;
			} else if($in_collection > 0)
			{

				$in_collection = 1;
				return $in_collection;
			}
		}
	}

}