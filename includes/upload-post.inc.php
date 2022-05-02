<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if(isset($_SESSION['post_Title']) && isset($_SESSION['post_Description']) && isset($_SESSION['userName']))
{

        $postId = $_SESSION['post_Id'];
        $postTitle = $_SESSION['post_Title'];
        $postDescription = $_SESSION['post_Description'];
        $postContents = $_SESSION['post_Contents'];
        $postAuthor = $_SESSION['post_Author'];
        $postDateCreated = $_SESSION['post_DateCreated'];

        if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] != chopStringToRoot(__FILE__) .'\images\placeholder.gif')
        {
            $postPathToImage = $_SESSION['post_PathToImage'];
        }
        else if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] == chopStringToRoot(__FILE__) .'\images\placeholder.gif')
        {
            $postPathToImage = $_SESSION['post_PathToImage'];
        }
        else
        {
            $postPathToImage = "";
        }



        $sql = "INSERT INTO `posts`
        (`id`, `title`, `description`, `contents`, `author`, `pathToImage`, `dateCreated`)
        VALUES (?, ?, ?, ?, ?, ?, ?);";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) // MYSQLI ERROR
        {
            unset($_SESSION['post_Id']);
            unset($_SESSION['post_Title']);
            unset($_SESSION['post_Description']);
            unset($_SESSION['post_Contents']);
            unset($_SESSION['post_Author']);
            unset($_SESSION['post_DateCreated']);
        
            if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] == chopStringToRoot(__FILE__) .'\images\placeholder.gif')
            {
                unset($_SESSION['post_PathToImage']);
            }
            else if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] != chopStringToRoot(__FILE__) .'\images\placeholder.gif')
            {
                foreach($postPathToImage as $key => $val)
                {
                    unlink('../uploads/images/' . chopStringToImageName($val));
                }
                unset($_SESSION['post_PathToImage']);
            }
        
            header("Location: ../admin.php?error=sqlError");
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "sssssss",
            $postId,
            $postTitle, 
            $postDescription, 
            $postContents, 
            $postAuthor, 
            json_encode($postPathToImage),
            $postDateCreated);

            mysqli_stmt_execute($stmt);

            if(!empty(mysqli_stmt_error($stmt))) // MYSQLI ERROR
            {
                unset($_SESSION['post_Id']);
                unset($_SESSION['post_Title']);
                unset($_SESSION['post_Description']);
                unset($_SESSION['post_Contents']);
                unset($_SESSION['post_Author']);
                unset($_SESSION['post_DateCreated']);
            
                if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] != chopStringToRoot(__FILE__) .'\images\placeholder.gif')
                {
                    foreach($postPathToImage as $key => $val)
                    {
                        unlink('../uploads/images/' . chopStringToImageName($val));
                    }
                    unset($_SESSION['post_PathToImage']);
                }
                else if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] == chopStringToRoot(__FILE__) .'\images\placeholder.gif')
                {
                    unset($_SESSION['post_PathToImage']);
                }

                header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                exit();
            }
            else //SUCCESS
            {                
                unset($_SESSION['post_Id']);
                unset($_SESSION['post_Title']);
                unset($_SESSION['post_Description']);
                unset($_SESSION['post_Contents']);
                unset($_SESSION['post_Author']);
                unset($_SESSION['post_DateCreated']);
            
                if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] != chopStringToRoot(__FILE__) .'\images\placeholder.gif')
                {
                    unset($_SESSION['post_PathToImage']);
                }
                else if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] == chopStringToRoot(__FILE__) .'\images\placeholder.gif')
                {
                    unset($_SESSION['post_PathToImage']);
                }
                
                // FACEBOOK ALERT
                if(!empty($_SESSION['FBRLH_state']) && !empty($_SESSION['page'])){

                    $id = $_SESSION['page']['id']. '/';
                    $access_token = $_SESSION['page']['access_token'];
                    include('../fb-init.php');
                
                    $arr = array('message' => 
$postTitle . '

' . $postDescription . '

' . 'https://dewandelmannen.nl/post.php?postId='.$postId);

                    $res = $fb->post($id.'feed/', $arr,	$access_token);
                    header("Location: ../admin.php?notif=upload&notif2=fbUpload&postId=".$postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
                else{
                    header("Location: ../admin.php?notif=upload&postId=".$postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
                
            }


        }
}
else // ERROR DOORVERWIJZING
{
    unset($_SESSION['post_Id']);
    unset($_SESSION['post_Title']);
    unset($_SESSION['post_Description']);
    unset($_SESSION['post_Contents']);
    unset($_SESSION['post_Author']);
    unset($_SESSION['post_DateCreated']);

    if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] != chopStringToRoot(__FILE__) .'\images\placeholder.gif')
    {
        foreach($postPathToImage as $key => $val)
        {
            unlink('../uploads/images/' . chopStringToImageName($val));
        }
        unset($_SESSION['post_PathToImage']);
    }
    else if(isset($_SESSION['post_PathToImage']) && $_SESSION['post_PathToImage'] == chopStringToRoot(__FILE__) .'\images\placeholder.gif')
    {
        unset($_SESSION['post_PathToImage']);
    }

    header("Location: ../admin.php?error=unvalidatedPhpActivation");
    exit();
}