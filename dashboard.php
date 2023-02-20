<?php session_start(); ?>

<?php
function getNumber($code)
{
    $dsn = "mysql:host=localhost;dbname=project";
    $user_host_name = "root";
    $password_host = "";
    $connect = new PDO($dsn, $user_host_name, $password_host);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $connect->prepare($code);
    $stmt->execute();
    return $stmt;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="./css/stylead.css">
    <link rel="stylesheet" href="./css/stylepages.css">

</head>

<body>

    <!--##########         Interface Student                                 #######-->
    <?php if (!isset($_SESSION["user"])) {
        header("Location:index.php");
        exit();
    } else { ?>

        <?php include 'header.php' ?>
        <h2 style="text-align: center;">Hello  <?php echo $_SESSION['user'] ?></h2>
        <div id="main">
            
            <?php if ($_SESSION["statut"] == 3) { ?>
                
                <div class="main-child">
                    <h2>Total of Profs</h2>
                    <h3><?php echo getNumber("select * from users WHERE statut=2 and class LIKE '%".$_SESSION["class"]."%'")->rowCount() ?></h3>
                    <a href="pages.php?page=Personnes">See them</a>
                </div>
            <?php } else { 
                // put classes of this teacher in $_SESSION
                $_SESSION["classes"]=getNumber("select * from users WHERE statut=2 AND id=".$_SESSION["id"])->fetch()["class"];
                
                ?>
                <div class="main-child">
                <h2>Total of Students</h2>
                <h3><?php if($_SESSION["classes"]==NULL){echo"0";}else{echo getNumber("select * from users where statut=3 AND class in (".$_SESSION["classes"].")")->rowCount();} ?></h3>
                <a href="pages.php?page=Personnes">See them</a>
                </div>
                <div class="main-child">
                    <h2>Total of classes</h2>
                    <h3><?php  $arr=getNumber("select * from users WHERE statut=2 AND id=".$_SESSION["id"])->fetch()["class"];if($arr==NULL){echo"0";}else{echo sizeof(explode(',',$arr)); }?></h3>
                    <a href="pages.php?page=classes">See them</a>
                </div>

        </div>



<?php }
        }
?>


<script src="file.js"></script>
</body>

</html>