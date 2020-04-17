<?php 
/** 
 * LOGIN ADMIN 
 */ 
define('ROOTDOC', true); 

error_reporting(E_ALL); //jegliche Fehlermeldungen und Warnungen werden angezeigt

/** Load Global Settings
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/globalSettings.php");

/** Load SessionStarter
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/sessionStart.php");

//ceck if user wants to logout
if (isset($_POST['logout'])) 
{

    /** Load SessionDestroyer
    ========================================= */
    require_once($_SERVER['DOCUMENT_ROOT'] . "/HybridCMS/Helper/sessionDestroy.php");
}

/** include classLoader
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Helper/autoload.php');

/** Load PasswordHash
========================================= */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Admin/Auth/PasswordHash.php');
?>
<?php
//log user in
if (isset($_POST['name'], $_POST['pass'])) {

    $username = $_POST['name'];
    $password = $_POST['pass'];


    // Passwords should never be longer than 72 characters to prevent DoS attacks
    if (strlen($password) > 72) {
        die("Password must be 72 characters or less");
    }

    //create Database-Object
    $dbAuth = new \HybridCMS\Database\DBAuth();

    //open Database-Connection
    $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

    $arrAuth = $dbAuth->selectUserRolenameAndPassByUsername($db, $username);
    
    //close Database-Connection
    \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

    $hasher = new PasswordHash(6, false);
    $check = $hasher->CheckPassword($password, $arrAuth['hash']);

    if ($check == 1) {

        //Login was sucessful -> redirect user to adminpage
        $_SESSION['username'] = $username;
        $_SESSION['rolename'] = $arrAuth['rolename'];
        
        header('Location: ' . HYB_PROTOCOL . HYB_HOST_NAME . '/admin/index.php');
    } else {
        echo "Passwort stimmt nicht!";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>        
    </head>
    <body>
        <!-- Primary Page Layout
        ================================================== -->
        <div class="container">
            <div class="row" id="mainContent" itemprop="mainContentOfPage">
                <article class="col-md-7">
                    <form style="margin-top: 30%;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <label>Name:</label><br />
                        <input class="form-control" type="text" name="name" /><br />
                        <label>Passwort:</label><br />
                        <input class="form-control" type="password" name="pass" /><br />
                        <input style="float:right;" class="btn-success" type="submit" value="Login" />
                    </form>
                </article>
            </div><!-- end .row -->
        </div><!-- container -->
    </body>
</html>