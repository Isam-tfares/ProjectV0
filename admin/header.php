<?php if (!isset($_SESSION["usernameAdmin"])) {
    header("Location:index.php");
    exit();
} ?>
<div id="Header">
    <div id="account">
        <div id="Profile"><?php echo $_SESSION["usernameAdmin"]; ?></div>
        <div><a href="<?php echo $_SERVER['PHP_SELF'] . '?do=deconnect' ?>">Deconnect</a></div>

    </div>
    <div id="navbarre">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="./pages.php?page=Students">List Etudiants</a></li>
            <li><a href="./pages.php?page=Profs">List Profs</a></li>
            <li><a href="./pages.php?page=classes">List Classes</a></li>

        </ul>
    </div>
</div>
<!-- Deconnect-->
<?php if (isset($_GET['do']) && $_GET['do'] == 'deconnect') {
    session_unset();
    session_destroy();
    header("Location:index.php");
    exit();
} ?>