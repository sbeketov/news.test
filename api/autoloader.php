<?php


function myAutoload($classNameWithNamespace)
{
	$pathToFile = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithNamespace) . '.php';

	if(file_exists($pathToFile)) {
		include "$pathToFile";
	} else {
		echo 'Файл' . $pathToFile . ' не существует';
	}
}
