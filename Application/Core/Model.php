<?php


namespace Application\Core;

use Application\Lib\Db;

abstract class Model
{
	public $db;

	public function __construct()
	{
		$this->db = new Db;
	}
}