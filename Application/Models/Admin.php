<?php


namespace Application\Models;

use Application\Core\Model;
use Imagick;
use ImagickDraw;

class Admin extends Model
{

	public $error;

	public function loginValidate($post)
	{
			$config = require __DIR__.'/../Config/admin.php';
			if ($config['login'] != $post['login'] or $config['password'] != $post['password']){
				$this->error = 'Логин или пароль указан не верно.';
				return false;
			}

			return true;

	}

	public function postValidate($post, $type)
	{
		$namelen = mb_strlen($post['name'], utf8);
		$description = mb_strlen($post['description'], utf8);
		$textlen = mb_strlen($post['text'], utf8);
		if($namelen < 3 or $namelen > 100 )
		{
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		}elseif($description < 3 or $description > 100 )
		{
			$this->error = 'Описание должно содержать от 3 до 100 символов';
			return false;
		}elseif($textlen < 10 or $textlen > 5000 )
		{
			$this->error = 'Текст должен содержать от 10 до 5000 символов';
			return false;
		}

		if($type == 'add' and empty($_FILES['img']['tmp_name']))
		{
				$this->error = 'Изображение не выбрано';
				return false;
		}

		return true;

	}


	public function postAdd($post)
	{
		$params = [
		'id' => '',
		'name' => $post['name'],
		'description' => $post['description'],
		'text' => $post['text'],
		];
		$this->db->query('INSERT INTO posts VALUES (:id, :name, :description, :text, NOW())', $params);
		return $this->db->lastInsertId();
	}

	public function postEdit($post, $id)
	{
		$params = [
			'id' => $id,
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->db->query('UPDATE posts SET name = :name, description = :description, text = :text WHERE id = :id', $params);

	}

	public function postUploadImage($path, $id)
	{
		$img = new Imagick($path);
		$draw = new ImagickDraw();
		$img->cropThumbnailImage(1024,768);
		$img->setImageCompressionQuality(90);
		// $img->borderImage('green', 100, 100); //  рамка вокруг изображения
		/* Черный текст */
		//$draw->setFillColor('red');

		/* Настройки шрифта */
		//$draw->setFont('Bookman-DemiItalic'); //__DIR__.'/../../public/fonts/impact.ttf'
		//$draw->setFontSize( 30 );
		//$draw->setFontStretch(\Imagick::STRETCH_ULTRAEXPANDED);


		/* Создаем текст */
		//$img->annotateImage($draw, 240, 720, 0, 'Травка зеленеет, солнышко блестит');

		$img->writeImage('public/materials/'.$id.'.jpg');
		//move_uploaded_file($path, 'public/materials/'.$id.'.jpg');
	}

	public function isPostExists($id)
	{
		$params = [
			'id' => $id,
		];
		return $this->db->column('SELECT id FROM posts WHERE id = :id', $params);
	}

	public function postDelete($id)
	{
		$params = [
			'id' => $id,
		];
		$this->db->query('DELETE FROM posts WHERE id = :id', $params);
		unlink('public/materials/'.$id.'.jpg');
	}

	public function postData($id)
	{
		$params = [
			'id' => $id,
		];
		return $this->db->row('SELECT * FROM posts WHERE id = :id', $params);

	}

}
