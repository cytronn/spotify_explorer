<?php namespace app\Controllers;

class SignupController
{
  public function __construct($app)
  {
    $this->app = $app;
  }

  public function createForm()
  {
    // Create builder
  $form_builder = $this->app['form.factory']->createBuilder();

  // Set method and action
  $form_builder->setMethod('post');
  $form_builder->setAction($this->app['url_generator']->generate('signup'));

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
    'email',
    'email',
    array(
        'label'      => 'Your email',
        'trim'       => true,
        'max_length' => 50,
        'required'   => true,
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

  $form_builder->add(
    'confirm',
    'password',
    array(
      'label' => 'Confirm your password',
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
    $password = $form_data["password"];
    $confirm = $form_data["confirm"];
    $name = $form_data["name"];
    $email = $form_data["email"];

    $validPasswords = false;
    if( $password === $confirm)
    {
      $validPasswords = true;
    }
    // is valid
    if($this->contact_form->isValid() && $validPasswords === true)
    {
      $is_available = $User->checkAvailability($name, $email);

      if($is_available['name'] === 1 && $is_available['mail'] === 1){
        $User->insertOne($name, $email, $password);
        return 'redirect';
      }
      else {
        $data = array();
        if($is_available['name'] === 0)
        {
          $data['name'] = 'Ce pseudonyme existe déjà';
        }
        if($is_available['mail'] === 0)
        {
          $data['mail'] = 'Cette adresse mail est déjà utilisée';
        }
        return $data;
      }
    }
  }
}



