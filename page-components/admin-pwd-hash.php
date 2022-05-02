<?php

echo '
    <div class="box" id="hashAnchor"> 
    <h3>Password hasher (voor development)</h3>
    <p><i>Het manueel hashen van wachtwoorden, via het Bcrypt5.5.0-algoritme. Bedoeld voor manuele invoer van gebruikers in de SQL-database.</i></p>

    <form method="POST" action="#hashAnchor" >
    <div class="row gtr-uniform">
    
    <div class="col-9 col-12-xsmall">
        <h4>Wachtwoord:</h4>
        <input type="password" name="pwd-hash" id="pwd-hash">
    </div>

    <div class="col-5 col-9-small col-12-xsmall">
        <input type="submit" name="submit-hash" id="submit-hash" style="display: none;">
        <label for="submit-hash" class="button fit solid">Wachtwoord hashen</label>
    </div>

    <div class="col-9 col-12-xsmall">
        <h4>Gehasht wachtwoord:</h4>
        <p name="hashedpwd" id="hashedpwd" class="box"><i style="word-wrap: break-word;">';

if(!empty($_POST["pwd-hash"]))
{
    $password = $_POST["pwd-hash"];
    $crypter = password_hash($password, PASSWORD_DEFAULT);
}

if(empty($crypter))
{
    echo "Field is empty!";
}
else
{
    echo $crypter;
}
                                
echo '</i></p></div></div></form></div>';

echo '<script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>';
