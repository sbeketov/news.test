'use strict';

const newsApi = 'api/news';
const filesApi = 'api/files';

const newsList = document.querySelector('.content .list-group');
const addForm = document.querySelector('#createNews form');
const updateForm = document.querySelector('#updateNews form');
const addButtonForm = addForm.querySelector('[data-submit]');
const updateButtonForm = updateForm.querySelector('[data-submit]');
let requestNews = [];

// функционал вставки данных в DOM
class News {

	constructor(data) {
		this.tag = 'li',
		this.cls = 'list-group-item',
		this.attrs = { 'data-id': data['id'] }
		this.content = {
			tag: 'div',
			cls: 'media',
			content: [{
				tag: 'img',
				cls: ['mr-3', 'image'],
				attrs: data['image_link'] ? { src: data['image_link'] } : { src: 'images/image_default.png' },
			},
			{
				tag:'div',
				cls: 'media-body',
				content: [{
					tag :'h5',
					cls : ['mt-0', 'title'],
					content: data['title']
				},
				{
					tag:'small',
					cls: 'text-muted',
					content: data['date_added']
				},
				{
					tag:'div',
					cls: ['mb-2', 'preview'],
					content: data['preview']
				},
				{
					tag:'button',
					cls: ['btn', 'btn-primary', 'btn-sm', 'mr-1'],
					attrs: { id: 'updateButton', 'data-toggle': 'modal', 'data-target': '#updateNews'},
					content: 'Редактировать'
				},
				{
					tag:'button',
					cls: ['btn', 'btn-danger', 'btn-sm'],
					attrs: { id: 'deleteButton' },
					content: 'Удалить'
				}],
			}]
		}
	}
}

function createElement(block) {

	if ((block === undefined) || (block === null) || (block === false)) {
		return document.createTextNode('');
	}
	if ((typeof block === 'number') || (typeof block === 'string') || (block === true)) {
		return document.createTextNode(block.toString());
	}

	if (Array.isArray(block)) {
		return block.reduce((f, item) => {
			f.appendChild(
				createElement(item)
			);

			return f;
		}, document.createDocumentFragment());
	}

	const element = document.createElement(block.tag);

	if (block.cls) {
		element.classList.add(...[].concat(block.cls));
	}

	if (block.attrs) {
		Object.keys(block.attrs).forEach(key => {
			element.setAttribute(key, block.attrs[key]);
		});
	}

	if (block.content) {
		element.appendChild(createElement(block.content));
	}

	return element;
}

// обработчики

addButtonForm.addEventListener('click', () => sendData(addForm, 'post'));
updateButtonForm.addEventListener('click', () => sendData(updateForm, 'put'));


// загружаем новости
getNews();

// функции запросов

// получаем список новостей
function getNews(){

	fetch(newsApi)
	.then(response => response.json())
	.then(result => insertNews(result))
	.catch(err => {
		console.log(err);
	})
}

function sendData(form, method) {

	const formData = new FormData(form);
	const data = {
		title: formData.get('title'),
		preview: formData.get('preview'),
		content: formData.get('content'),
	};

	if(formData.get('image').size > 0) {
		uploadImage(formData).then(res => {
			const link = res['data']['link'] ? res['data']['link'] : null;
			if (method === 'post') {
				sendPostRequest(data, link)
			} else if(method === 'put') {
				sendPutRequest(data, link);
			}
		});
	} else {
		if (method === 'post') {
			sendPostRequest(data)
		} else if(method === 'put') {
			sendPutRequest(data)
		}
	}
	form.reset();
}

function sendPostRequest(data, link = null) {

	data['image_link'] = link;
	const body = JSON.stringify(data);

	fetch(newsApi, {
		method: 'POST',
		headers: {
			'Content-type': 'application/json; charset=UTF-8'
		},
		body: body
	})
	.then(response => response.json())
	.then(result => insertNews(result))
	.catch(err => {
		console.log(err);
	});
}

function sendPutRequest(data, link = null) {

	const currentLink = updateForm.getAttribute('data-image') === 'default' ? null : updateForm.querySelector('.image').src;
	data['image_link'] = link ? link : currentLink;

	console.log(currentLink);

	const body = JSON.stringify(data);
	const id = updateForm.getAttribute('data-id');

	fetch(`${newsApi}\\${id}`, {
		method: 'PUT',
		headers: {
			'Content-type': 'application/json; charset=UTF-8'
		},
		body: body
	})
	.then(response => response.json())
	.then(result => updateNews(result, id))
	.catch(err => {
		console.log(err);
	});
}

function sendDeleteRequest(id) {

	fetch(`${newsApi}\\${id}`, {
		method: 'DELETE',
	})
	.then(response => deleteNews(response, id))
	.catch(err => {
		console.log(err);
	});
}

function uploadImage(form) {

	form.delete('title');
	form.delete('preview');
	form.delete('content');
	return fetch(filesApi, {
		method: 'POST',
		body: form,
	})
	.then(response => response.json())
	.then(result => result)
	.catch(err => {
		console.log(err);
	});
}

function insertNews(response) {

	if(!response.data) {
		return;
	}
	requestNews = response['data'];
	requestNews.forEach(item => {
		const news = createElement(new News(item));
		const updateButton = news.querySelector('#updateButton');
		const deleteButton = news.querySelector('#deleteButton');
		updateButton.addEventListener('click', () => insertDataToForm(item.id));
		deleteButton.addEventListener('click', () => sendDeleteRequest(item.id));
		newsList.appendChild(news);
	});
}

function insertDataToForm(id) {

	requestNews.forEach(item => {
		if(item.id == id) {
			updateForm.setAttribute('data-id', id);
			updateForm.querySelector('#title').value = item.title;
			updateForm.querySelector('#preview').textContent  = item.preview;
			updateForm.querySelector('#content').textContent  = item.content;
			if(item['image_link']) {
				updateForm.querySelector('.image').src  = item['image_link'];
				updateForm.setAttribute('data-image', 'custom');
			} else {
				updateForm.querySelector('.image').src  = 'images/image_default.png';
				updateForm.setAttribute('data-image', 'default');
			}
		}
	})
}

function updateNews(response, id) {

	if(!response.data) {
		return;
	}
	const data = response.data;
	const news = newsList.querySelector(`[data-id="${id}"]`);
	news.querySelector('h5').textContent = data[0]['title'];
	news.querySelector('.preview').textContent = data[0]['preview'];
	news.querySelector('.preview').textContent = data[0]['preview'];
	if(data[0]['image_link']) {
		news.querySelector('.image').src = data[0]['image_link'];
	}
}

function deleteNews(response, id) {

	if(response.ok) {
		newsList.querySelector(`[data-id="${id}"]`).remove();
	}
}
