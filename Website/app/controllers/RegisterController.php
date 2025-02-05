<?php

class RegisterController extends Controller {

    public function __construct($controller, $action){
        parent::__construct($controller,$action);
        $this->load_model('Users');
        $this->view->setLayout('default');

    }

    public function loginAction() {
        
        // echo password_hash('password', PASSWORD_DEFAULT);
        $validation = new Validate();
        if($_POST) {
            // $validation = true;
            // if($validation) {
            //     $user = $this->UsersModel->findByUsername($_POST['username']);
            //     dnd($user);
            // }
            //from valisation
            $validation->check($_POST,[
                'username' =>[
                    'display' => "Username",
                    'required' => true
                ],
                'password' => [
                    'display' => 'password',
                    'required' => true,
                    'min' => 6
                ]
            ]);
            
            if($validation->passed()) {
                $user = $this->UsersModel->findByUsername($_POST['username']);
                if($user && password_verify(Input::get('password'), $user->password)) {
                    $remember = (isset($_POST['remember_me']) && Input::get('remember_me')) ? true :false;
                    $user->login($remember);
                    Router::redirect('');
                } else {
                    $validation->addError("There is an error with your uesrname or password");
                }
            } 
        }
        $this->view->displayErrors = $validation->displayErrors();
        $this->view->render('register/login');
    }

    public function logoutAction() {
        if(currentUser()) {
            currentUser()->logout();
        }
        Router::redirect('');
    }

    public function registerAction() {
        $validation = new Validate();
        $posted_values = ['fname' =>'','lname' => '','username'=>'','email'=>'','email'=>'','password'=>'','confirm'=>''];
        if($_POST) {
            $posted_values = posted_values($_POST);
            $validation->check($_POST, [
                'fname' => [
                    'display' => 'First Name',
                    'required' => true
                ],
                'lname' => [
                    'display' => 'Last Name',
                    'required' => true
                ],
                'username' => [
                    'display'=> 'Username',
                    'required'=> true,
                    'unique' => 'users',
                    'min' => 6,
                    'max' => 150
                ],
                'email' => [
                    'display'=> 'Email',
                    'required'=> true,
                    'unique' => 'users',
                    'max' => 150
                ],
                'password' => [
                    'display' => 'Password',
                    'required' => true,
                    'min' => 6
                ],
                'confirm' => [
                    'display' => 'Confirm Password',
                    'required' =>true,
                    'matches' => 'password'
                ]

            ]);

            if($validation->passed()) {
                $newUser = new Users();
                $newUser->registerNewUser($_POST);
                // $newUser->login();
                Router::redirect('register/login');
            }
        }
        $this->view->post = $posted_values;
        $this->view->displayErrors = $validation->displayErrors();
        $this->view->render('register/register');
    }
}