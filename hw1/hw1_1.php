<?php

/* Написать аналог «Проводника» в Windows для директорий на сервере при помощи итераторов. */

function explorer() : void
{
    showInside(getPath());
}

function getPath() : string
{
    if ($_GET['f']) {
        return $_GET['f'];
    }
    return '/';
}

function showInside($path) : void
{
    $catalog = new DirectoryIterator($path);
    $pathParam = '';
    foreach ($catalog as $item) {
        if ($item->getPath() !== '/') {
            $pathParam = $item->getPath();
        }
        if ($item->isFile()) {
            echo $item . '<br>';
        } else {
            echo "<a href='?f={$pathParam}/{$item}'>$item</a><br>";
        }
    }
}

explorer();
