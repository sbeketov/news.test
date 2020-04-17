<?php

require_once(__DIR__ . '/autoloader.php');
spl_autoload_register('myAutoload');

use news\News;

$news = new News;

if (!empty($_GET)) {
	$action = strip_tags($_GET['action']);
	if($action == 'delete') $news->deleteNews($_GET);
}

if (!empty($_POST)) {
	if($action == 'create') $news->createNews($_POST, $_FILES);
	if($action == 'update') $news->updateNews($_POST, $_FILES);
	header("Location: ".$_SERVER["REQUEST_URI"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Редактирование новостей</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="content w-75 p-3 m-auto">
		<h1>Редактирование новостей</h1>

		<!-- Button trigger modal create -->
		<button type="button" class="btn btn-primary m-4" data-toggle="modal" data-target="#createNews">Добавить новость</button>

		<ul class="list-group">
			<?foreach($news->getNewsList() as $new):?>

				<li class="list-group-item">
					<div class="media">
						<? if(isset($new['image']) && isset($new['image_type'])):
							$img = base64_encode($new['image']);
						?>
							<img src="data:<?=$new['image_type']?>;base64,<?=$img?>" class="mr-3 image">
						<?else:?>
							<img src="img/image_default.png" class="mr-3 image">
						<?endif?>
						<div class="media-body">
							<h5 class="mt-0"><?=$new['title']?></h5>
							<small id="emailHelp" class="text-muted"><?=$new['date_added']?></small>
							<div class="mb-2"><?=$new['preview']?></div>
							<a href="?id=<?=$new['id']?>&edit=true" class="btn btn-primary btn-sm">редактировать</a>
							<a href="<?='?id=' . $new['id'] . '&action=delete' ?>" class="btn btn-danger btn-sm">удалить</a>
						</div>
					</div>
				</li>
			<?endforeach?>
		</ul>
	</div>

<!-- Modal Create -->

<div class="modal fade" id="createNews" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered h-75" style="max-width: 80%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Добавление новости</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

		<form action="index.php?action=create" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="date_added" id="" value="<?=date('Y-m-d G:i:s')?>">

			<div class="modal-body">
				<div class="form-group">
					<label for="title">Название</label>
					<input type="text" class="form-control" id="title"  name="title" placeholder="Название" required>
				</div>
				<div class="form-group">
					<label for="preview">Анонс</label>
					<textarea class="form-control" id="preview" name="preview" rows="3" placeholder="Анонс" required></textarea>
				</div>
				<div class="form-group">
					<label for="content">Текст новости</label>
					<textarea class="form-control" id="content" name="content" rows="10" placeholder="Текст новости" required></textarea>
				</div>
				<div class="form-group">
					<label for="image">Добавить изображение</label>
					<input type="file" class="form-control-file" id="image" name="image">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				<button type="submit" class="btn btn-primary">Сохранить</button>
			</div>

		</form>

    </div>
  </div>
</div>
<!-- End Modal Create -->


<!-- Button trigger modal create -->
<button id="updateButton"  class="d-none" data-toggle="modal" data-target="#updateNews"></button>

<!-- Modal Update -->
<? $new = $news->getNews($_GET['id']);?>

<div class="modal fade" id="updateNews" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-my-size" style="max-width: 80%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Редактирование новости</h5>
        <a href="index.php" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>

		<form action="index.php?action=update" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="id" id="" value="<?=$new['id']?>">

			<div class="modal-body">
				<div class="form-group">
					<label for="title">Название</label>
					<input type="text" class="form-control" id="title" name="title" value="<?=$new['title']?>" required>
				</div>
				<div class="form-group">
					<label for="preview">Анонс</label>
					<textarea class="form-control" id="preview" name="preview" rows="3" required><?=$new['preview']?></textarea>
				</div>
				<div class="form-group">
					<label for="content">Текст новости</label>
					<textarea class="form-control" id="content" name="content" rows="10" required><?=$new['content']?></textarea>
				</div>
				<div class="form-group">
					<? if(isset($new['image']) && isset($new['image_type'])):
						$img = base64_encode($new['image']);
					?>
						<img src="data:<?=$new['image_type']?>;base64,<?=$img?>" class="mr-3 image image-edit">
						<label for="image">Изменить изображение</label>
					<?else:?>
						<label for="image">Добавить изображение</label>
					<?endif?>
					<input type="file" class="form-control-file" id="image" name="image" value="">
				</div>
			</div>
			<div class="modal-footer">
				<a href="index.php" class="btn btn-secondary" data-dismiss="modal">Закрыть</a>
				<button type="submit" class="btn btn-primary">Сохранить</button>
			</div>
		</form>
    </div>
  </div>
</div>

<!-- End Modal Edite -->

<?if($_GET['edit'] === 'true'): ?>
	<script>
		document.getElementById("updateButton").click();
	</script>
<?endif?>

</body>
</html>
