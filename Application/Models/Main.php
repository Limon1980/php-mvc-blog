<?php

namespace Application\Models;


use Application\Core\Model;
use Application\Lib\Pagination;



class Main extends Model
{

	public $error;

	public function contactValidate($post)
	{

		$namelen = mb_strlen($post['name'], utf8);
		$textlen = mb_strlen($post['text'], utf8);
		if($namelen < 3 or $namelen > 20 )
		{
			$this->error = 'Имя должно содержать от 3 до 20 символов';
			return false;
		}elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$this->error = 'E-mail указан не верно';
			return false;
		}elseif($textlen < 10 or $textlen > 500 )
		{
			$this->error = 'Сообщение от 10 до 500 символов';
			return false;
		}


		return true;
	}

	public function postsCount()
	{
	return $this->db->column('SELECT COUNT(id) FROM posts');
	}

	public function postsList($route)
	{
		$max = 5;
		$params = [
			'max' => $max,
			'start' => (($route['page'] ?? 1) - 1) * $max,
		];
		return $this->db->row('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
	}


}
