<?php

namespace Application\Controllers;


use Application\Core\Controller;

class AccountController extends Controller
{


	public function loginAction()
	{
		if(!empty($_POST)){
			//$this->view->location('/account/register');
			$this->view->message('success', 'вошли');
		}
		//$this->view->redirect('https://google.com');
		$this->view->render('Вход');
	}

	public function registerAction()
	{
		//$this->view->layout = 'custom';
		$this->view->render('Регистрация');
	}




}
