<?php

if(isset($_SESSION['userName']) && isset($_SESSION['userId']))
{
    include_once 'includes/dbh.inc.php';

    $sql = "SELECT * FROM `posts` ORDER BY `id` DESC;";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

}
else
{
    header("Location: admin.php?error=unvalidatedPhpActivation");
    exit();
}

?>

<div class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Blog ID:</th>
				<th>Titel:</th>
				<th>Beschrijving:</th>
                <th>Auteur:</th>
                <th>Datum:</th>
			</tr>
		</thead>
		<tbody>
            <?php

                if($resultCheck > 0)
                {
                    $array = array();

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $array[] = $row;
                    }
                    
                    foreach($array as $key => $value)
                    {
                        echo '<tr>';
                        echo '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">'.$array[$key]['id'].'</td>';

                        echo '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 175px;">
                        <a style="border-bottom: none;" href=post.php?postId='.$array[$key]['id'].'>'.$array[$key]['title'].'</a></td>';

                        echo '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; font-size:80%; font-style:italic;">'.$array[$key]['description'].'</td>';
                        echo '<td style="font-style: italic;">'.$array[$key]['author'].'</td>';
                        echo '<td style="font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 125px;">'.$array[$key]['dateCreated'].'</td>';
                        echo '<td><a href="edit.php?postId='.$array[$key]['id'] .'" class="icon regular fa-edit"></a>';
                        echo '</tr>';
                    }
                    


                }
                
                else
                {
                    echo '<tr>';
                    echo '<td colspan="5"><i style="color:red;">Er is geen data gevonden in de database! Begin nu met posts maken, via het formulier hierboven.</i></td>';
                    echo '</tr>';
                }

            ?>
		</tbody>
	</table>
</div>