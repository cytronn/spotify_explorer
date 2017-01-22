<?php namespace app\Controllers;

class LoginController
{
	public function __construct($app){
		$this->app = $app;
	}

	public function createForm()
	{
		  // Create builder
  		$form_builder = $this->app['form.factory']->createBuilder();

	  	// Set method and action
	  	$form_builder->setMethod('post');
	  	$form_builder->setAction($this->app['url_generator']->generate('login'));

	  	// Add inputs
	  	$form_builder->add(
		    'name',
		    'text',
		    array(
		      'label' => 'Your name',
		      'trim' => true,
		      'max_length' => 50,
		      'required' => true,
		    )
	  	);

 	 	$form_builder->add(
    		'password',
		    'password',
	    	array(
    	  	'label' => 'Your password',
	      	'trim' => true,
	      	'max_length' => 50,
	      	'required' => true,
      		)
   	 	);
	  	$form_builder->add('submit', 'submit');

  		// Create Form
  		$contact_form = $form_builder->getForm();
  		$this->contact_form = $contact_form;

  		return $contact_form;
	}
	
	public function validateForm($form_data, $User)
  	{
		$name = $form_data["name"];
	    $password = $form_data["password"];

	    if($this->contact_form->isValid())
	    {
	    	$login_user = $User->logIn($name,$password);
	    	if ($login_user == 'redirect')
	    	{
	    		return 'redirect';
	    	}
	    }
  	}
}


