<?php

namespace models;

use lib\Db;

class News
{
	public static function getAll()
	{
		$db = new Db;
		$result = $db->row('SELECT * FROM news');
		return $result;

		try {
			$db = new Db;
			$result = $db->row('SELECT * FROM news');
			return $result;
		} catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
			return false;
		}
	}

	public static function getById($id)
	{
		try {
			$db = new Db;
			$result = $db->row('SELECT * FROM news WHERE id = :id', ['id' => $id]);
			return $result;
		} catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
			return false;
		}
	}

	public static function create($params)
	{
		try {
			$db = new Db;
			$sql = "INSERT INTO news (date_added, title, preview, content, image_link) VALUES (:date_added, :title, :preview, :content, :image_link)";
			$db->query($sql, $params);
			$result = $db->row("SELECT * FROM news ORDER BY id DESC LIMIT 1");
			return $result;
		} catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
			return false;
		}
	}

	public static function update($params)
	{
		try {
			$db = new Db;
			$sql = "UPDATE news SET title = :title, preview = :preview, content = :content, image_link = :image_link WHERE id = :id";
			$db->query($sql, $params);
			return true;
		} catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
			return $e->getMessage();
		}
	}

	public static function deleteById($id)
	{
		try {
			$db = new Db;
			$db->query("DELETE FROM news WHERE id=:id ", ['id' => $id]);
			return true;
		} catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
			return false;
		}
	}
}
