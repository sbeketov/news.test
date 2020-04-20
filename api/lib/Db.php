<?php

namespace lib;

use PDO;

class Db
{

	protected $db;

	public function __construct()
	{
		$config = require(__DIR__."/../../config.php");
		$this->db = new PDO('mysql:host='. $config['db']['host'] . ';dbname=' . $config['db']['name'], $config['db']['user'], $config['db']['password']);
	}

	public function query($sql, $params = [])
	{
		$stmt = $this->db->prepare($sql);
		if(!empty($params)) {
			foreach ($params as $key => $val) {
				$stmt->bindValue(':'. $key, $val);
			}
		}
		$stmt->execute();
		return $stmt;
	}

	public function row($sql, $params = [])
	{
		$result = $this->query($sql, $params);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	public function column($sql, $params = [])
	{
		$result = $this->query($sql, $params);
		return $result->fetch(PDO::FETCH_ASSOC);
	}

}
