<?php namespace app\Controllers;

use Symfony\Component\Validator\Constraints;


class AlbumController
{
	public function __construct($app)
	{
		$this->app = $app;
	}

	public function getInformations($album_id, $Album)
	{
		$album = $Album;
		$result = $album->getInformations($album_id);

		$result = json_decode($result);

		$response = new \stdClass();

		// Artist informations
		$response->artist = new \stdClass();
		$response->artist->id = $result->artists[0]->id;
		$response->artist->name = $result->artists[0]->name;
		// Album informations
		$response->album = new \stdClass();
		$response->album->name = $result->name;
		$response->album->release_date = $result->release_date;
		$response->album->image = $result->images[0]->url;
		$response->album->copyright = $result->copyrights[0]->text;
		// Tracks informations
		$response->tracks = array();
		for ($i=0; $i < count($result->tracks->items) ; $i++) { 
			$response->tracks[$i] = new \stdClass();
			$response->tracks[$i]->track_number = $result->tracks->items[$i]->track_number;
			if($response->tracks[$i]->track_number < 10)
			{
				$response->tracks[$i]->track_number = '0'.$response->tracks[$i]->track_number;
			}
			$response->tracks[$i]->name = $result->tracks->items[$i]->name;
			$response->tracks[$i]->preview_url = $result->tracks->items[$i]->preview_url;
			$response->tracks[$i]->duration = intval($result->tracks->items[$i]->duration_ms);
			$response->tracks[$i]->duration = $this->timeTranslation($response->tracks[$i]->duration);
			
		}

		return $response;
	}

	public function createForm($album_id, $user_id, $Album)
	{

		$album = $Album;
		$in_collection = $album->inCollection($album_id, $user_id);
		 // Create builder
  		$form_builder = $this->app['form.factory']->createBuilder();

	  	// Set method and action
	  	$form_builder->setMethod('post');
	  	$form_builder->setAction($this->app['url_generator']->generate('album',array('album_id' => $album_id)));

	  	if($in_collection == 0)
	  	{
	  		$form_builder->add('Ajouter aux favoris', 'submit');

	  	} else 
	  	{
	  		$form_builder->add('Retirer des favoris', 'submit');	  		
	  	}


  		// Create Form
  		$contact_form = $form_builder->getForm();
  		$this->contact_form = $contact_form;
  		$data = array(
  			'contact_form' => $contact_form,
  			'in_collection' => $in_collection
		);
  		return $data;
	}

	public function validateForm($album_informations, $album_id, $Album, $user_id, $in_collection, $request)
	{
		$album = $Album;
		$album->addOne($album_informations, $album_id);

		if($in_collection == 1)
		{
			$Album->removeFromCollection($album_id, $user_id, $request);
		}
		else 
		{
			$Album->addToCollection($album_id, $user_id);
		}


	}

	public function getCollection($user_id, $User)
	{
		$user = $User;
		$user = $user->getOneCollection($user_id);
		return $user;
	}

	/*
	* $duration, a duration in milliseconds (integer)
	*/
	public function timeTranslation($duration)
	{
		$uSec = $duration % 1000;
			$duration = $duration / 1000;
			$sec = $duration % 60;
			if($sec < 10)
			{
				$sec = '0'.$sec;
			}
			$duration = $duration / 60;
			$min = $duration % 60;
			$duration = $min.':'.$sec;

			return $duration;
	}
}