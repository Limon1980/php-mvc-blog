<?php

namespace Application\Controllers;


use Application\Core\Controller;
use Application\Lib\Pagination;
use Application\Models\Main;


class AdminController extends Controller
{
	public function __construct($route)
	{
		parent::__construct($route);
		$this->view->layout = 'admin';

	}

	public function loginAction()
	{	if (isset($_SESSION['admin']))
			{
				$this->view->redirect('admin/add');
			}
		if (!empty($_POST)) {
			if (!$this->model->loginValidate($_POST)) {
				$this->view->message('error', $this->model->error);
			}
			$_SESSION['admin'] = true;
			$this->view->location('admin/add');
			//$this->view->layout = 'admin';
		}
		$this->view->render('Страница входа');
	}

	public function logoutAction()
	{
		unset($_SESSION['admin']);
		$this->view->redirect('');

	}

	public function postsAction()
	{
		$mainModel = new Main;
		$pagination = new Pagination($this->route, $mainModel->postsCount());
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $mainModel->postsList($this->route),
		];
		$this->view->render('Посты', $vars);
	}

	public function addAction()
	{
		if (!empty($_POST)) {
			if (!$this->model->postValidate($_POST, 'add')) {
				$this->view->message('error', $this->model->error);
			}
			$id = $this->model->postAdd($_POST);
			if (!$id){
			$this->view->message('success', 'Ошибка обработки запроса');
			}
			$this->model->postUploadImage($_FILES['img']['tmp_name'], $id);
			$this->view->message('success', 'Пост добавлен');
		}

		$this->view->render('Добавить пост');
	}

	public function editAction()
	{
		if (!$this->model->isPostExists($this->route['id']))
		{
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if (!$this->model->postValidate($_POST, 'edit')) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->postEdit($_POST, $this->route['id']);
			if($_FILES['img']['tmp_name'])
			{
				$this->model->postUploadImage($_FILES['img']['tmp_name'], $this->route['id']);
			}
			$this->view->message('success', 'Сохранено');
		}
		$vars = [
			'data' => $this->model->postData($this->route['id'])[0],
		];
		$this->view->render('Редактирование', $vars);
	}

	public function deleteAction()
	{
		//debug($this->model->isPostExists($this->route['id']));
		if (!$this->model->isPostExists($this->route['id']))
		{
			$this->view->errorCode(404);
		}
		$this->model->postDelete($this->route['id']);
		$this->view->redirect('admin/posts');
		//exit('Удаление: id: '. $this->route['id']);
	}







}