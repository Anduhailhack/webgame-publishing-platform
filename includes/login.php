<?php
if (session_status() != PHP_SESSION_ACTIVE)
    session_start();

include_once("./utils/globals.php");
include_once("./utils/validators.php");
include_once("./db/conn.php");

?>
<link rel="stylesheet" href="./css/login-style.css">
<div class="container">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="login-form">
        <div class="form-control-container">
            <h2 class="caption">Admin - Login</h2>
        </div>
        <div class="form-control-container">
            <p class="warning" id="warning">
                <?php
                if (
                    isset($_POST["email"]) &&
                    isset($_POST["pass"])
                ) {
                    $email = $_POST["email"];
                    $pass = hash("sha256", $_POST["pass"] . SALT);

                    if (isValidEmail($email) == 1) {
                        try {
                            $db = new DbConnection(DSN, USER, PASS);
                            $value = $db->getUser($email, $pass);
                            if (
                                $value &&
                                (count($value) >= 1) &&
                                ($email == $value["user"]) &&
                                ($pass == $value["pass"])
                            ) {
                                $_SESSION["email"] = $value["user"];
                                $_SESSION["user_id"] = $value["user_id"];
                                echo '<script>window.location="main.php"</script>';
                                // echo $_SESSION["email"] . " " . $_SESSION["user_id"];
                            } else {
                                if (session_status() == PHP_SESSION_ACTIVE)
                                    session_destroy();
                                echo "Unknown username or password.";
                            }
                        } catch (Exception $ex) {
                            echo '<script>window.location="internal_error.php"</script>';
                            die("Unexpected internal error");
                        }
                    }
                }
                ?>
            </p>
        </div>
        <div class="form-control-container">
            <label class="form-label" for="email">Email address</label>
            <input type="email" placeholder="Email" id="email" name="email" class="form-control-in-txt">
        </div>
        <div class="form-control-container">
            <label class="form-label" for="pass">Password</label>
            <input type="password" placeholder="Password" id="pass" name="pass" class="form-control-in-txt">
        </div>
        <div class="form-control-container">
            <input type="submit" id="sub-btn" name="subm" value="Login" class="btn">
        </div>
    </form>
</div>
<script src="./js/login.js"></script>