<?php

namespace controllers;

use models\News;

class NewsController extends Controller
{
    public $apiName = 'news';

    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/api/news
     * @return string
     */
    public function indexAction()
    {
		$news = News::getAll();
        if($news){
            return $this->response($news, 200);
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/api/news/1
     * @return string
     */
    public function viewAction()
    {
        //id должен быть первым параметром после /news/x
        $id = array_shift($this->requestUri);

        if($id){
            $result = News::getById($id);
            if($result){
                return $this->response($result, 200);
            }
        }
		return $this->response('Data not found', 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/api/news
     * @return string
     */
    public function createAction()
    {
        $params = [];

        $params['title'] = strip_tags($this->requestParams->title ?? '');
        $params['preview'] = strip_tags($this->requestParams->preview) ?? '';
        $params['content'] = strip_tags($this->requestParams->content) ?? '';
		$params['image_link'] = strip_tags($this->requestParams->image_link) ?? NULL;
		$params['date_added'] = date("Y-m-d H:i:s");

        if($params['title'] && $params['preview'] &&$params['content']){

            if($result = News::create($params)){
                return $this->response($result, 201);
            }
            return $this->response("Saving error", 400);
        }
        return $this->response('Not enough parameters', 422);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/api/news/1
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $newsId = $parse_url['path'] ?? null;

        if(!$newsId || !News::getById($newsId)){
            return $this->response("News with id=$newsId not found", 404);
        }

        $params = [];
        $params['id'] = strip_tags($newsId);
		$params['title'] = strip_tags($this->requestParams->title ?? '');
        $params['preview'] = strip_tags($this->requestParams->preview) ?? '';
        $params['content'] = strip_tags($this->requestParams->content) ?? '';
        $params['image_link'] = strip_tags($this->requestParams->image_link) ?? NULL;

        if($params['title'] && $params['id'] && $params['preview'] &&$params['content']){
            if(News::update($params)){
                $result = News::getById($newsId);
                return $this->response($result, 202);
            }
            return $this->response("Saving error", 400);
        }
        return $this->response('Unprocessable Entity', 422);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/api/news/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $newsId = $parse_url['path'] ?? null;

        if(!$newsId || !News::getById($newsId)){
            return $this->response("New with id=$newsId not found", 404);
        }
        if(News::deleteById($newsId)){
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }

}
