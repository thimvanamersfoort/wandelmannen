<?php

session_start();

function unsetSessionVariables()
{
    unset($_SESSION['post_Id']);
    unset($_SESSION['post_Title']);
    unset($_SESSION['post_Description']);
    unset($_SESSION['post_Contents']);
    unset($_SESSION['post_Author']);
    unset($_SESSION['post_DateCreated']);
    unset($_SESSION['tempPath']);
}
function deleteOldPhotos($postOldPathToPhoto)
{
    if(!empty($postOldPathToPhoto))
    {
        foreach($postOldPathToPhoto as $key => $val)
        {
            unlink('../uploads/images/' . chopStringToImageName($val));
        }
    }
}

if(!isset($_SESSION['userName']) || !isset($_SESSION['userId']) || !isset($_POST['submit']))
{
    unset($_SESSION['tempId']);
    unset($_SESSION['tempPath']);
    header("Location: ../admin.php?error=unvalidatedPhpActivation");
    exit();
}
else if(!isset($_SESSION['tempId']) || !isset($_SESSION['tempPath']))
{ 
    unset($_SESSION['tempId']);
    unset($_SESSION['tempPath']);
    header("Location: ../admin.php?error=unvalidatedPhpActivation");
    exit();
}
else if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['contents']))
{
    header("Location: ../edit.php?error=emptyFields");
    exit();
}
else
{
    require_once 'functions.inc.php';

    $postId = $_SESSION['tempId'];

    $postOldPathToPhoto = json_decode($_SESSION['tempPath'], true);
    $postNewPathToPhoto = $_FILES['image'];

    if(empty($postOldPathToPhoto))
    {
        if(in_array(4, $_FILES['image']['error'])) // DONT UPDATE PHOTO
        {
            

            require 'dbh.inc.php';

            $postId = $_SESSION['tempId'];
            $postTitle = $_POST['title'];
            $postDescription = $_POST['description'];
            $postContents = $_POST['contents'];
            $postAuthor = $_SESSION['userName'];
            $postDateCreated = date("d-m-Y");
            $postPathToImage = $_SESSION['tempPath'];
            
        
            $sql = "UPDATE `posts` SET `title`=?, `description`=?,`contents`=?, `author`=?, `pathToImage`=?, `dateCreated`=? WHERE `id`=?;";
        
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                unsetSessionVariables();
                header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "sssssss",
                $postTitle, 
                $postDescription, 
                $postContents, 
                $postAuthor, 
                $postPathToImage,
                $postDateCreated,
                $postId);
        
                mysqli_stmt_execute($stmt);
        
                if(!empty(mysqli_stmt_error($stmt)))
                {
                    unsetSessionVariables();

                    header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                    exit();
                }
                else
                {
                    unsetSessionVariables();
        
                    header("Location: ../admin.php?notif=edit&postId=". $postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
            }
        }
        else if(!in_array(4, $_FILES['image']['error'])) // UPDATE PHOTO
        {
            // delete old photo

            //hoeft niet, want er is geen oude foto gevonden

            //upload nieuwe foto + database

            require 'dbh.inc.php';

            $postId = $_SESSION['tempId'];
            $postTitle = $_POST['title'];
            $postDescription = $_POST['description'];
            $postContents = $_POST['contents'];
            $postAuthor = $_SESSION['userName'];
            $postDateCreated = date("d-m-Y");
            $postPathToImageOld = $_SESSION['tempPath'];

            $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
            $imageNames = array_filter($_FILES['image']['name']);
            $post_PathToImage = array();
            $failedFiles = array();
            $index = 0;

            if(!empty($imageNames))
            {   
                foreach($_FILES['image']['name'] as $key=>$val)
                {
                    $imageName = $_FILES['image']['name'][$key];
                    $tmpFileExt = explode('.', $imageName);
                    $fileExt = strtolower(end($tmpFileExt));

                    if(in_array($fileExt, $allowedExt))
                    {
                        if($_FILES['image']['error'][$key] === 0)
                        {
                            if($_FILES['image']['size'][$key] < 20000000)
                            {
                                date_default_timezone_set("Europe/Amsterdam");
                                $imageNameNew = date("dmY_His") . "_" . $_SESSION['userName'] . "_" . $index . "." . $fileExt;
                                $imageDest = '../uploads/images/'.$imageNameNew;

                                $index += 1;

                                move_uploaded_file($_FILES['image']['tmp_name'][$key], $imageDest); 
                                
                                array_push($post_PathToImage, chopStringToRoot(__FILE__.$imageNameNew) . '\uploads\images\\' . $imageNameNew);
                            }
                            else
                            {
                                array_push($failedFiles, $_FILES['image']['name'][$key]);
                            }
                        }
                        else
                        {
                            array_push($failedFiles, $_FILES['image']['name'][$key]);
                        }
                    }
                    else
                    {
                        array_push($failedFiles, $_FILES['image']['name'][$key]);
                    }
                }
            }
            // update database
            $sql = "UPDATE `posts` SET `title`=?, `description`=?,`contents`=?, `author`=?, `pathToImage`=?, `dateCreated`=? WHERE `id`=?;";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                unset($_SESSION['tempPath']);
                header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "sssssss",
                $postTitle, 
                $postDescription, 
                $postContents, 
                $postAuthor, 
                json_encode($post_PathToImage),
                $postDateCreated,
                $postId);

                mysqli_stmt_execute($stmt);

                if(!empty(mysqli_stmt_error($stmt)))
                {
                    unset($_SESSION['tempPath']);
                    header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                    exit();
                }
                else
                {
                    unset($_SESSION['tempPath']);
                    header("Location: ../admin.php?notif=edit&postId=". $postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
            }
        }
    }
    else if(!empty($postOldPathToPhoto))
    {
        if(in_array(4, $_FILES['image']['error'])) // DONT UPDATE PHOTO
        {
            require 'dbh.inc.php';

            $postId = $_SESSION['tempId'];
            $postTitle = $_POST['title'];
            $postDescription = $_POST['description'];
            $postContents = $_POST['contents'];
            $postAuthor = $_SESSION['userName'];
            $postDateCreated = date("d-m-Y");
            $postPathToImage = $_SESSION['tempPath'];
        
            $sql = "UPDATE `posts` SET `title`=?, `description`=?,`contents`=?, `author`=?, `dateCreated`=? WHERE `id`=?;";
        
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                unsetSessionVariables();
                header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "ssssss",
                $postTitle, 
                $postDescription, 
                $postContents, 
                $postAuthor,
                $postDateCreated,
                $postId);
        
                mysqli_stmt_execute($stmt);
        
                if(!empty(mysqli_stmt_error($stmt)))
                {
                    unsetSessionVariables();
                    header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                    exit();
                }
                else
                {
                    unsetSessionVariables();
                    header("Location: ../admin.php?notif=edit&postId=". $postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
            }
        }
        else if(!in_array(4, $_FILES['image']['error'])) // UPDATE PHOTO
        {
            // delete old photo

            deleteOldPhotos($postOldPathToPhoto);

            //upload nieuwe foto + database
            print_r($postOldPathToPhoto);

            echo '<br><br>';

            print_r($postNewPathToPhoto);
            
            require 'dbh.inc.php';

            $postId = $_SESSION['tempId'];
            $postTitle = $_POST['title'];
            $postDescription = $_POST['description'];
            $postContents = $_POST['contents'];
            $postAuthor = $_SESSION['userName'];
            $postDateCreated = date("d-m-Y");
            $postPathToImageOld = $_SESSION['tempPath'];

            $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
            $imageNames = array_filter($_FILES['image']['name']);
            $post_PathToImage = array();
            $failedFiles = array();
            $index = 0;

            if(!empty($imageNames))
            {   
                foreach($_FILES['image']['name'] as $key=>$val)
                {
                    $imageName = $_FILES['image']['name'][$key];
                    $tmpFileExt = explode('.', $imageName);
                    $fileExt = strtolower(end($tmpFileExt));

                    if(in_array($fileExt, $allowedExt))
                    {
                        if($_FILES['image']['error'][$key] === 0)
                        {
                            if($_FILES['image']['size'][$key] < 20000000)
                            {
                                date_default_timezone_set("Europe/Amsterdam");
                                $imageNameNew = date("dmY_His") . "_" . $_SESSION['userName'] . "_" . $index . "." . $fileExt;
                                $imageDest = '../uploads/images/'.$imageNameNew;

                                $index += 1;

                                move_uploaded_file($_FILES['image']['tmp_name'][$key], $imageDest); 
                                
                                array_push($post_PathToImage, chopStringToRoot(__FILE__.$imageNameNew) . '\uploads\images\\' . $imageNameNew);
                            }
                            else
                            {
                                array_push($failedFiles, $_FILES['image']['name'][$key]);
                            }
                        }
                        else
                        {
                            array_push($failedFiles, $_FILES['image']['name'][$key]);
                        }
                    }
                    else
                    {
                        array_push($failedFiles, $_FILES['image']['name'][$key]);
                    }
                }
            }
            // update database
            $sql = "UPDATE `posts` SET `title`=?, `description`=?,`contents`=?, `author`=?, `pathToImage`=?, `dateCreated`=? WHERE `id`=?;";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql))
            {
                unset($_SESSION['tempPath']);
                header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "sssssss",
                $postTitle, 
                $postDescription, 
                $postContents, 
                $postAuthor, 
                json_encode($post_PathToImage),
                $postDateCreated,
                $postId);

                mysqli_stmt_execute($stmt);

                if(!empty(mysqli_stmt_error($stmt)))
                {
                    unset($_SESSION['tempPath']);
                    header("Location: ../admin.php?error=sqlError&errorMsg=".mysqli_stmt_error($stmt));
                    exit();
                }
                else
                {
                    unset($_SESSION['tempPath']);
                    header("Location: ../admin.php?notif=edit&postId=". $postId ."&threadId=" . mysqli_thread_id($conn));
                    exit();
                }
            }
            
        }
    }
}