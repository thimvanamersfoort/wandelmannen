<?php

echo '
    <div class="box" id="dateAnchor"> 
    <h3>Idate Converter (voor development)</h3>
    <p><i>Het converten van een datum naar een PHP Idate Object.</i></p>

    <form method="POST" action="#dateAnchor" >
    <div class="row gtr-uniform">
    
    <div class="col-9 col-12-xsmall">
        <h4>Datum:</h4>
        <input type="text" name="date-id" id="date-id">
    </div>

    <div class="col-5 col-9-small col-12-xsmall">
        <input type="submit" name="submit-date" id="submit-date" style="display: none;">
        <label for="submit-date" class="button fit solid">Naar Idate</label>
    </div>

    <div class="col-9 col-12-xsmall">
        <h4>Idate vanaf UNIX Epoch:</h4>
        <p name="idate" id="idate" class="box"><i style="word-wrap: break-word;">';

if(!empty($_POST["date-id"]))
{
    $date = $_POST["date-id"];
    $time = strtotime($date);
}

if(empty($time))
{
    echo "Field is empty!";
}
else
{
    echo $time;
}
                                
echo '</i></p></div></div></form></div>';

echo '<script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>';
