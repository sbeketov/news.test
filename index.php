<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Редактирование новостей</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/main.js" defer></script>
</head>
<body>
<div class="content w-75 p-3 m-auto">
	<h1>Редактирование новостей</h1>

	<!-- Button trigger modal create -->
	<button type="button" class="btn btn-primary m-4" data-toggle="modal" data-target="#createNews">Добавить новость</button>

	<ul class="list-group">
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

		<form action="" method="POST" enctype="multipart/form-data">

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
				<button type="button" class="btn btn-primary" data-submit data-dismiss="modal">Сохранить</button>
			</div>

		</form>

    </div>
  </div>
</div>

<!-- Modal Update -->

<div class="modal fade" id="updateNews" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-my-size" style="max-width: 80%" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Редактирование новости</h5>
        <a href="index.php" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>

		<form action="" method="PUT" enctype="multipart/form-data">
		<input type="hidden" name="id" id="" value="">

			<div class="modal-body">
				<div class="form-group">
					<label for="title">Название</label>
					<input type="text" class="form-control" id="title" name="title" value="Заголовок" required>
				</div>
				<div class="form-group">
					<label for="preview">Анонс</label>
					<textarea class="form-control" id="preview" name="preview" rows="3" required>Анонс</textarea>
				</div>
				<div class="form-group">
					<label for="content">Текст новости</label>
					<textarea class="form-control" id="content" name="content" rows="10" required>Текст</textarea>
				</div>
				<div class="form-group">
						<img src="" class="mr-3 image image-edit">
						<label for="image">Изменить изображение</label>
					<input type="file" class="form-control-file" id="image" name="image" value="">
				</div>
			</div>
			<div class="modal-footer">
				<a href="index.php" class="btn btn-secondary" data-dismiss="modal">Закрыть</a>
				<button type="button" class="btn btn-primary" data-submit data-dismiss="modal">Сохранить</button>
			</div>
		</form>
    </div>
  </div>
</div>

</body>
</html>
