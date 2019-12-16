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
                cl.parent_id,
                cl.level
              FROM `categories_db` AS `c`
              INNER JOIN `category_links` AS `cl`
              ON c.id_category = cl.child_id";
    $res = mysqli_query($link, $query);
    $cats = [];
    while ($item = mysqli_fetch_assoc($res)) {
        if ($item['id_category'] === $item['parent_id']) {
            $cats['nodes'][$item['id_category']] = $item;
        }
        if ($item['id_category'] !== $item['parent_id']) {
            $cats['branches'][$item['id_category']] = $item;
        }
    }
    mysqli_close($link);
    return $cats;
}

function buildTree($cats, $node)
{
    if (is_array($cats) && isset($cats['nodes'][$node])) {
        $tree = "<li>" . $cats['nodes'][$node]['category_name'];
        $tree .= "<ul>";
        foreach ($cats['branches'] as $cat) {
            if ($cat['parent_id'] == $node) {
                if (isset($cats['nodes'][$cat['id_category']])) {
                    $tree .= buildTree($cats, $cat['id_category']);
                } else {
                    $tree .= "<li>" . $cat['category_name'] . "</li>";
                }
            }
        }
        $tree .= "</ul></li>";
        return "<ul>" . $tree . "</ul>";
    }
    return null;
}

echo getTree();
