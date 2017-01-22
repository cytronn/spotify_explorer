<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

// App
$app = new Silex\Application();

// Config
$app['debug'] = true;


// Cache 
$cache = new Gilbitron\Util\SimpleCache();
$cache->cache_path = dirname(__DIR__).'/cache/';
$cache->cache_time = 3600;

// Session
$app->register(new Silex\Provider\SessionServiceProvider());


// Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.options' => array('cache' => dirname(__DIR__).'/cache', 'strict_variables' => true),
));

// URL generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'spotify_explorer',
        'user'      => 'root',
        'password'  => 'root',
        'charset'   => 'utf8'
    ),
));


$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app['db']->setFetchMode(PDO::FETCH_OBJ);


//Models
$Album = new app\Models\Album($app, $cache);
$album_controller = new app\Controllers\AlbumController($app);
$User = new app\Models\User($app);
$signup_controller = new app\Controllers\SignupController($app);
$Artist = new app\Models\Artist($app, $cache);
$artist_controller = new app\Controllers\ArtistController($app);
$search_controller = new app\Controllers\SearchController($app);
$Search = new app\Models\Search($app);
$login_controller = new app\Controllers\LogInController($app);

/*
* ROUTES
**/

// Home
$app->match('/', function() use ($app){
   $data = array(
      'title' => 'Spotify Explorer'
   );

  return $app['twig']->render('pages/home.twig', $data);
})
->bind('home');

// Signup route
$app->match('/signup', function(Request $request) use ($app, $User, $signup_controller){

  $app['session']->start();

  if(null !== $app['session']->get('name'))
  {
      return $app->redirect($app['url_generator']->generate('search'));
  }

  $contact_form = $signup_controller->createForm();
 
  $data = array(
      'title' => 'Spotify Explorer – Sign up',
      'main_title' => 'Soon we will welcome you !',
      'contact_form' => $contact_form->createView()
  );
  // Handle request
  $contact_form->handleRequest($request);

  // Is submitted
  if($contact_form->isSubmitted())
  {
    // Get form data
    $form_data = $contact_form->getData();
    $validated = $signup_controller->validateForm($form_data, $User);
    if($validated == 'redirect')
    {
      return $app->redirect($app['url_generator']->generate('search'));
    }
    $data['name'] = $validated['name'];
    $data['mail'] = $validated['mail'];
  }  

  return $app['twig']->render('pages/signup.twig', $data);
})
->bind('signup');

// Search route
$app->get('/search', function() use($app){
  $app['session']->start();

  $data = array(
    'user_id' => $app['session']->get('id'),
    'title' => 'Spotify Explorer – Search',
    'user' => array()
  );

  if($app['session']->get('name'))
  {
    $data['user']['name'] = $app['session']->get('name');
  }
  return $app['twig']->render('pages/search.twig', $data);
})
->bind('search');

// Artist route
$app->get('/artist/{artist_id}', function($artist_id) use($app, $cache, $Artist, $artist_controller){
   $app['session']->start();

  $artist_informations = $artist_controller->getInformations($artist_id, $Artist);
  $artist_informations = json_decode($artist_informations);

  $artist_albums = $artist_controller->getAlbums($artist_id, $Artist);

  $artist_top_track = $artist_controller->getTopTrack($artist_id, $Artist);

  $data = array(
    'title' => 'Spotify Explorer – '.$artist_informations->name,
    'user_id' => $app['session']->get('id'),
    'informations' => array(
      'name' => $artist_informations->name,
      'followers' => $artist_informations->followers->total,
      'big_image' => $artist_informations->images[0]->url,
      'short_image' => $artist_informations->images[2]->url,
      ),
    'albums' => $artist_albums,
    'toptrack' => $artist_top_track
    );

  return $app['twig']->render('pages/artist.twig', $data);

})
->bind('artist');

// Album route
$app->match('/album/{album_id}', function($album_id, Request $request) use($app, $cache, $Album, $album_controller)
{
   $app['session']->start();

   // User informations
   $user = array(
      'id' => $app['session']->get('id')
   );

   $album_informations = $album_controller->getInformations($album_id, $Album);
   $album_form = $album_controller->createForm($album_id, $user['id'], $Album);
 
   $data = array(
      'title' => 'Spotify Explorer – '.$album_informations->album->name,
      'user_id' => $user['id'],
      'form' => $album_form['contact_form']->createView(),
      'artist'  => $album_informations->artist,
      'album'   => $album_informations->album,
      'tracks'  => $album_informations->tracks,
   );

   $album_form['contact_form']->handleRequest($request);
      if($album_form['contact_form']->isSubmitted())
   {
      // Get form data
      $form_data = $album_form['contact_form']->getData();
      $add_fav = $album_controller->validateForm($album_informations->album, $album_id, $Album, $user['id'], $album_form['in_collection'], $request);
   }

   if($app['session']->get('name'))
   {
      $data['user']['name'] = $app['session']->get('name');
   }

   return $app['twig']->render('pages/album.twig', $data);
})
->bind('album');

// User route
$app->get('/user/{user_id}', function($user_id) use($app, $cache, $User, $album_controller)
{
   $app['session']->start();

   $collection = $album_controller->getCollection($user_id, $User);
   $data = array(
      'user_id' => $app['session']->get('id'),
      'name' => $app['session']->get('name'),
      'title' => 'Spotify Explorer – '. $app['session']->get('name'),
      'collection' => $collection
   );
   return $app['twig']->render('pages/user.twig', $data);
})
->bind('user');

// AJAX SEARCH
$app->get('/search-results', function() use ($app, $search_controller, $Search)
{
  $query = $_GET['q'];
  $result = $search_controller->research($query,$Search);
  return $result;
});

$app->match('/login', function(Request $request) use ($app, $User, $login_controller)
{
  $app['session']->start();
  if(null !== $app['session']->get('name'))
  {
      return $app->redirect($app['url_generator']->generate('search'));
  }
  $contact_form = $login_controller->createForm();

  $data = array(
      'main_title' => 'Welcome back, fellow ! 👻',
      'title' => 'Spotify Explorer – Login',
    'contact_form' => $contact_form->createView()
  );

  // Handle request
  $contact_form->handleRequest($request);

  // Is submitted
  if($contact_form->isSubmitted())
  {
  
    // Get form data
    $form_data = $contact_form->getData();
    $validated = $login_controller->validateForm($form_data, $User);
    if($validated == 'redirect')
    {
      return $app->redirect($app['url_generator']->generate('search'));
    }
  }
  return $app['twig']->render('pages/signup.twig', $data);
})
->bind('login');

$app->match('/logout', function() use ($app)
{
  if(!isset($app['session'])){
    $app['session']->start();
  }
  $app['session']->clear();
  return $app->redirect($app['url_generator']->generate('search'));
})
->bind('logout');

// $app->error(function() use($app)
// {
//   $data = array(
//     'title' => 'Error'
//   );
//   return $app['twig']->render('pages/404.twig', $data);
// });

$app->run();


