<?php

function getCats()
{
    $link = mysqli_connect('localhost', 'root', '', 'cats_db');
    $query = "SELECT * FROM `nested_sets` ORDER BY `left`";
    $res = mysqli_query($link, $query);
    $cats =[];
    while ($item = mysqli_fetch_assoc($res)) {
        $cats[$item['level']][$item['id']] = $item;
    }
    mysqli_close($link);
    var_dump($cats);
    return $cats;
}

function buildTree( array  $arr)
{
   $tree = "<ul>";

   foreach ($arr as $key => $value) {
//       var_dump($value);
       $tree .= "<li>" . $value['value'];
       $tree .= " [] ";
       $tree .= "</li>";
   }

   $tree .= "</ul>";
   return $tree;
}

echo buildTree(getCats());
