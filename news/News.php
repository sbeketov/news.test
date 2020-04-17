<?php

namespace news;
use lib\Db;

class News
{

	protected $db;

	public function __construct()
	{
		$this->$db = new Db;
	}

	public function getNewsList()
	{
		$result = $this->$db->row('SELECT * FROM news');
		return $result;
	}

	public function getNews($id)
	{
		$result = $this->$db->column('SELECT * FROM news WHERE id = :id', ['id' => $id]);
		return $result;
	}

	public function createNews(...$params)
	{
		$checkedParams = $this->setParams($params);
		$sql = "INSERT INTO news (date_added, title, preview, content) VALUES (:date_added, :title, :preview, :content)";
		if(isset($checkedParams['image']) && isset($checkedParams['image_type'])) {
			 $sql = "INSERT INTO news (date_added, title, preview, content, image, image_type) VALUES (:date_added, :title, :preview, :content, :image, :image_type)";
		}
		$this->$db->query($sql, $checkedParams);
	}

	public function updateNews(...$params)
	{
		$checkedParams = $this->setParams($params);
		$sql = "UPDATE news SET title = :title, preview = :preview, content = :content WHERE id = :id";
		if(isset($checkedParams['image']) && isset($checkedParams['image_type'])) {
			$sql = "UPDATE news SET title = :title, preview = :preview, content = :content, image = :image, image_type = :image_type WHERE id = :id";
		}
		$this->$db->query($sql, $checkedParams);
	}

	public function deleteNews($params)
	{
		$this->$db->query("DELETE FROM news WHERE id=:id ", ['id' => $params['id']]);
	}

	public function setParams(...$params)
	{
		$result = [];
		foreach ($params[0] as $keyParam => $param) {
			if(is_array($param)) {
				$checkedParam = $this->checkParams($param);
				foreach ($checkedParam as $keyVal => $val) {
					$result[$keyVal] = $val;
				}
			} else {
				$result[$keyParam] = $param;
			}
		}
		return $result;
	}

	public function checkParams($params)
	{
		$result = [];

		isset ($params['id']) ? $result['id'] = strip_tags($params['id']) : false;
		isset ($params['title']) ? $result['title'] = strip_tags($params['title']) : false;
		isset ($params['date_added']) ? $result['date_added'] = strip_tags($params['date_added']) : false;
		isset ($params['preview']) ? $result['preview'] = strip_tags($params['preview']) : false;
		isset ($params['content']) ? $result['content'] = strip_tags($params['content']) : false;

		if(substr($params['image']['type'], 0, 5) === 'image') {
			!empty ($params['image']['tmp_name']) ? $result['image'] = file_get_contents($params['image']['tmp_name']) : false;
			$result['image_type'] = $params['image']['type'];
		}

		return $result;
	}

}
