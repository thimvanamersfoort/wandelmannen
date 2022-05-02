<?php

require_once 'includes/dbh.inc.php';
$postId = $_GET['postId'];

# PREV BUTTON
$sql = "SELECT * FROM posts where id = (select max(id) from posts where id < $postId)";
$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql))
{
    flush();
    ob_flush();
    header("Location: post.php?postId=".$postId."&error=sqlError1");
}
else
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result))
    {
        $prevId = $row['id'];
    }
    else{
        $prevId = "none"; 
    }
}

# NEXT BUTTON

$sql = "SELECT * FROM posts where id = (select min(id) from posts where id > $postId)";
$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql))
{
    flush();
    ob_flush();
    header("Location: post.php?postId=".$postId."&error=sqlError1");
}
else
{
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result))
    {
        $nextId = $row['id'];
    }
    else{
        $nextId = "none"; 
    }
}

echo '<div class="prev-next">';

if($prevId != 'none'){
    echo '<a href="post.php?postId='.$prevId.'" id="prev" class="button">Vorige</a>';
} else{
    echo '<a href="#" id="prev" class="button disabled">Vorige</a>';
}

if($nextId != 'none'){
    echo '<a href="post.php?postId='.$nextId.'" id="next" class="button">Volgende</a>';
} else{
    echo '<a href="#" id="next" class="button disabled">Volgende</a>';
}

echo '</div>';
