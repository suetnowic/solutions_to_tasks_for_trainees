<?php
require 'vendor/autoload.php';

use Arhitector\Yandex\Disk;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Управление файлами</title>
    <style>
        .directory {
            font-weight: bold;
            cursor: pointer;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php

    $token = '//здесь токен';

    $disk = new Disk($token);

    // Определяем текущий каталог на основе переданного параметра dir в URL
    $dir = isset($_GET['dir']) ? $_GET['dir'] : '/';
    ?>

    <h3>Загрузить файлы:</h3>
    <form class="row mt-4" action="index.php" method="post" enctype="multipart/form-data">
        <div class="col-sm-6">
            <input type="hidden" name="dir" value="<?= $dir ?>">
            <input class="form-control" type="file" name="file[]" multiple>
        </div>
        <div class="col-auto">
            <input class="btn btn-secondary mb-3" type="submit" name="add_file" value="Загрузить">
        </div>
    </form>

    <?php

    // Функция для отображения содержимого каталога
    function showDirectory($dir = 'disk:/')
    {
        global $disk;

        echo "<h2>{$dir}</h2>";

        // Получаем список файлов и каталогов в указанном каталоге
        $items = $disk->getResource($dir, 300)->items->toArray();

        echo "<div style='display: flex; flex-wrap: wrap;'>";
        foreach ($items as $item) {
            $name = $item->getIterator()['name'];

            // Если элемент является каталогом, добавляем класс "directory" для стилизации и назначаем обработчик события для перехода внутрь каталога
            if ($item->isDir()) {
                echo "<div class='directory' style='margin: 10px 20px; word-wrap: break-word' onclick='navigate(\"{$dir}/{$name}\")'>
                        <img src='img/folder.svg' alt=''>
                        <p>{$name}</p>
                        <form action='index.php' method='post'>
                            <input type='hidden' name='dir' value='{$dir}'>
                            <input type='hidden' name='delete_dir' value='{$name}'>
                            <input type='submit' name='delete' value='Удалить'>
                        </form>
                     </div>";
            } else {
                echo "<div style='margin: 26px 20px 10px 20px; word-wrap: break-word'>
                        <img src='img/file.svg' alt=''>
                        <p>{$name}</p>
                        <form action='index.php' method='post'>
                            <input type='hidden' name='dir' value='{$dir}'>
                            <input type='hidden' name='delete_file' value='{$name}'>
                            <input type='submit' name='delete' value='Удалить'>
                        </form>
                     </div>";
            }
        }
        echo "</div>";
    }


    showDirectory($dir);

    if (isset($_POST['add_file'])) {
        $uploadDir = $_POST['dir'];
        $files = $_FILES['file'];
        for ($i = 0; $i < count($files['name']); $i++) {
            $tmpName = $files['tmp_name'][$i];
            $name = $files['name'][$i];
            $uploadPath = $uploadDir . '/' . $name; // путь для сохранения
            $resource = $disk->getResource($uploadPath);
            if (!$resource->has()) {
                $resource->upload($tmpName);
            }
        }
    }

    if (isset($_POST['delete'])) {
        $deleteDir = $_POST['delete_dir'] ?? null;
        $deleteFile = $_POST['delete_file'] ?? null;
        $path = $_POST['dir'];

        if ($deleteDir && $disk->getResource($dir . '/' . $deleteDir)->isDir()) {
            $disk->getResource($dir . '/' . $deleteDir)->delete();
        } elseif ($deleteFile && $disk->getResource($path . '/' . $deleteFile)->isFile()) {
            $disk->getResource($path . '/' . $deleteFile)->delete();
        }
    }
    ?>
</div>
<script>
    // Функция для навигации из JS
    function navigate(dir) {
        location.href = 'index.php?dir=' + dir;
    }
</script>
</body>
</html>
