<?php

namespace controllers;

class FilesController extends Controller
{
	public $apiName = 'files';

	/**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/api/files
     * @return string
     */
    public function indexAction()
    {

	}

	    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/api/files/1
     * @return string
     */
    public function viewAction()
    {
		$parse_url = parse_url($this->requestUri[0]);
        $newsId = $parse_url['path'] ?? null;

    }


	/**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/api/files
     * @return string
     */

	public function createAction() {
		// return $this->response($this->requestFiles, 200);

        if(isset($this->requestFiles['image'])){
			$image = $this->requestFiles['image'];
			if ($image['size'] <= 0) {
				return ;
			}
            $result = [
                'errors' => [],
                'link' => ''
            ];

            // File info
            $file_name = $image['name'];
            $file_size = $image['size'];
            $file_tmp = $image['tmp_name'];
            $file_type = $image['type'];

            // Get file extension
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));

            // White list extensions
            $extensions = array("jpeg","jpg","png");

            // Check it's valid file for upload
            if(in_array($file_ext, $extensions) === false) {
               $result['errors'][] = "Extension not allowed, please choose a JPEG or PNG file.";
            }

            // Check file size
            if($file_size > 2097152) {
               $result['errors'][] = 'File size must be exactly 2 MB';
            }

            if(empty($result['errors']) == true) {
                try {
                    move_uploaded_file($file_tmp, "../images/" . $file_name);
                    $result['link'] = "images/" . $file_name;
                    return $this->response($result, 201);
                } catch (Exception $e) {
                    $result['errors'][] = $e->getMessage();
                }
            } else {
                return $this->response($result, 400);
            }
        }
	}

	 /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/api/files/1
     * @return string
     */
    public function updateAction()
    {
		$parse_url = parse_url($this->requestUri[0]);
        $newsId = $parse_url['path'] ?? null;

    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/api/files/1
     * @return string
     */
    public function deleteAction()
    {
		$parse_url = parse_url($this->requestUri[0]);
        $newsId = $parse_url['path'] ?? null;

    }

}
