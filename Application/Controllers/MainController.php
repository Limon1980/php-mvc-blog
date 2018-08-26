<?php

namespace Application\Controllers;


use Application\Core\Controller;
use Application\Lib\Pagination;
use Application\Models\Admin;
use PHPMailer\PHPMailer\PHPMailer;



class MainController extends Controller
{
	public function indexAction()
	{
//		$result = $this->model->getNews();
//		$vars = [
//			'news' => $result,

//		];

		$pagination = new Pagination($this->route, $this->model->postsCount());
		$vars = [
		 'pagination' => $pagination->get(),
		 'list' => $this->model->postsList($this->route),
		];
		$this->view->render('Главаная страница', $vars);
	}

	public function aboutAction()
	{
		$this->view->render('Обо мне');
	}

	public function contactAction()
	{
		if(!empty($_POST)){
			if (!$this->model->contactValidate($_POST)){
				 $this->view->message('error',  $this->model->error);
			}

			$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

				//Server settings
				$mail->SMTPDebug = 0;                                 // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.beget.com';  					  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'work1990@work1990.ru';                 // SMTP username
				$mail->Password = 'TRargo.12';                           // SMTP password
				$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('work1990@work1990.ru', 'Admin');
				$mail->addAddress('tarkalmob@yandex.ru', 'Yandex');     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');

				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Сообщение из блога от '. $_POST['name'].' почта '. $_POST['email'] ;
				$mail->Body    = '<p>'.$_POST['text']. '</p>  Ответить: '.$_POST['name'].'  на почту '. $_POST['email'];
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();


			$this->view->message('success', 'Сообщение отправлено Администратору.');

		}

		$this->view->render('Контакты');
	}

	public function postAction()
	{
		$adminModel = new Admin();
		if (!$adminModel->isPostExists($this->route['id']))
		{
			$this->view->errorCode(404);
		}
		$vars = [
			'data' => $adminModel->postData($this->route['id'])[0],

		];
		$this->view->render('Пост', $vars);
	}



	public function form1Action()
	{
		$this->view->layout = 'clear';
		$this->view->render('Форма 1');
	}

	public function form2Action()
	{
		$this->view->layout = 'clear';
		$this->view->render('Форма 2');
	}

	public function testAction()
	{
		$this->view->layout = 'test';
		$this->view->render('Тест');
	}




}