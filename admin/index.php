<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/stylead.css">
</head>

<body class="body-sign">
    <?php if(isset($_SESSION["usernameAdmin"])){
        header("Location:dashboard.php");
        exit();
    }?>
    <!--Errors -->
    <?php
    function getIfSet(&$value, $default = null)
    {
        return isset($value) ? $value : $default;
    }
    $message1i = getIfSet($_REQUEST["usernameSi"]);
    $message2i = getIfSet($_REQUEST["passwordSi"]);

    ?>
    <!--REQUEST FOR SIGN-->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username_value = ($_POST["username"]);
        $pass_value = ($_POST["pass"]);


        $dsn = "mysql:host=localhost;dbname=project";
        $user_host_name = "root";
        $password_host = "";
        try {
            $connect = new PDO($dsn, $user_host_name, $password_host);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $password_reel = false;
            $stmt = $connect->prepare("SELECT * FROM users WHERE username='$username_value' AND statut=1");
            $stmt->execute();
            $array = $stmt->fetchAll();
            if (count($array) > 0) {
                foreach ($array as $key => $value) {
                    if ($value["passwordUser"] == $pass_value) {
                        $password_reel = true;
                    }
                };
                if ($password_reel) {
                    session_start();
                    $_SESSION["usernameAdmin"] = $username_value;
                    header("Location:dashboard.php");
                    exit();
                } else {
                    header("Location:" . $_SERVER['PHP_SELF'] . "?passwordSi=password incorrect&username=" . $username_value);
                    exit();
                }
            } else {
                header("Location:" . $_SERVER['PHP_SELF'] . "?passwordSi=username incorrect&username=" . $username_value . "&password=" . $pass_value);
                exit();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } ?>





    <!--Body of page -->

    <!--  Sign in    -->
    <div style="display: flex;align-items: center;">
    <form class="form-admin" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4>Please Enter your data</h4>
        <label for="">username</label>
        <input type="text" name="username" value="<?php if (isset($_GET['username'])) {
                                                            echo $_GET['username'];
                                                        } ?>">
        <label for="">password</label>
        <input type="password" name="pass" value="<?php if (isset($_GET['password'])) {
                                                            echo $_GET['password'];
                                                        } ?>">
        <input type="submit" id="btn" value="Sign in">
    </form>

    <?php echo "<h3 class='messageError'>$message1i</h3><h3  class='messageError'>$message2i</h3>" ?>
    </div>



</body>

</html>