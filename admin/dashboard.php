<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="../css/stylead.css">
</head>

<body>
    <?php if (!isset($_SESSION["usernameAdmin"])) {
        header("Location:index.php");
        exit();
    } else { ?>

        <?php include 'header.php' ?>



        <h2 style="text-align: center;">Hello admin <?php echo $_SESSION['usernameAdmin'] ?></h2>
        <div class="main">
            <div class="main-child">
                <h2>Total of Students</h2>
                <h3><?php echo getNumber("users", "WHERE statut=3")->rowCount() ?></h3>
                <a href="./pages.php?page=Students">See them</a>
            </div>
            <div class="main-child">
                <h2>Total of Profs</h2>
                <h3><?php echo getNumber("users", "WHERE statut=2")->rowCount() ?></h3>
                <a href="pages.php?page=Profs">See them</a>
            </div>
            <div class="main-child">
                <h2>Total of Classes</h2>
                <h3><?php echo getNumber("classes", "users", "WHERE 1")->rowCount() ?></h3>
                <a href="pages.php?page=classes">See them</a>
            </div>
        </div>








    <?php }

    function getNumber($select, $where)
    {
        $dsn = "mysql:host=localhost;dbname=project";
        $user_host_name = "root";
        $password_host = "";
        $connect = new PDO($dsn, $user_host_name, $password_host);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $connect->prepare("SELECT * FROM $select $where");
        $stmt->execute();
        return $stmt;
    }
    ?>

</body>

</html>