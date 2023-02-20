
<?php if (!isset($_SESSION["user"]) || !isset($_SESSION["statut"])) {
    header("Location:index.php");
    exit();
} ?>
<div id="Header">
    <div id="navbarre">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <?php if($_SESSION["statut"]==3){ ?>
            <li><a href="pages.php?page=modules">Modules</a></li> <?php }?>
            <li><a href="pages.php?page=Faire">A faire</a></li>
            <li><a href="pages.php?page=Personnes">Personnes</a></li>
            <li><a href=<?php if($_SESSION["statut"]==3){echo"pages.php?page=NotesStudent";}else{echo"pages.php?page=Notes";}?>>Espace d'affichage</a></li>



        </ul>
    </div>
    <div id="account">
        <div class="menu hide">
            <ul>
                <li> <a href="pages.php?page=show">show profile</a></li>
                <li> <a href="pages.php?page=edit">edit profile</a></li>
                <li><a href="<?php echo $_SERVER['PHP_SELF'] . '?do=deconnect' ?>">Deconnect</a></li>
            </ul>
            
        </div>
        <div class="profile">
            <img class="headerImg" src="./imgsProfile/<?php echo $_SESSION["img"]; ?>" alt="">
            <div id="Profile"><?php echo $_SESSION["user"]; ?></div>
            <span class="show">^</span>
        </div>
        

    </div>

</div>
<!-- Deconnect-->
<?php if (isset($_GET['do']) && $_GET['do'] == 'deconnect') {
    session_unset();
    session_destroy();
    header("Location:index.php");
    exit();
} ?>
