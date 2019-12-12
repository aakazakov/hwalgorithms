<?php

/* Реализовать вывод меню на основе Clojure table. */

function getTree()
{
    return buildTree(getCats(), 1);
}

function getCats()
{
    $link = mysqli_connect('localhost', 'root', '', 'cats_db');
    $query = "SELECT
                c.id_category,
                c.category_name,
                cl.parent_id
              FROM `categories_db` AS `c`
              JOIN `category_links` AS `cl`
              ON c.id_category = cl.child_id";
    $res = mysqli_query($link, $query);
    $cats =[];
    while ($item = mysqli_fetch_assoc($res)) {
        $cats[$item['parent_id']][$item['id_category']] = $item;
    }
    mysqli_close($link);
    return $cats;
}

function buildTree($arrOfCats, $parent_id)
{
    if (is_array($arrOfCats) && isset($arrOfCats[$parent_id])) {
        $tree = "<ul>";

        foreach ($arrOfCats[$parent_id] as $cat) {
            $tree .= "<li>" . $cat['category_name'];
            if ($cat['id_category'] !== $parent_id) {
                $tree .= buildTree($arrOfCats, $cat['id_category']);
            }
            $tree .= "</li>";
        }

        $tree .= "</ul>";
        return $tree;
    }
    return null;
}

echo getTree();
