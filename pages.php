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
    <?php session_start(); ?>

    <?php
    function get($select, $from, $where)
    {
        $dsn = "mysql:host=localhost;dbname=project";
        $user_host_name = "root";
        $password_host = "";
        $connect = new PDO($dsn, $user_host_name, $password_host);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $connect->prepare("SELECT $select FROM $from $where");
        $stmt->execute();
        return $stmt;
    }
    function insert($code)
    {
        $dsn = "mysql:host=localhost;dbname=project";
        $user_host_name = "root";
        $password_host = "";
        $connect = new PDO($dsn, $user_host_name, $password_host);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $connect->prepare($code);
        $stmt->execute();
        return $stmt->rowCount();
    }

    if (!isset($_SESSION["user"])) {
        header("Location:index.php");
        exit();
    } else {
        include 'header.php';
        if (isset($_GET["page"])) {
            $page = $_GET["page"];

            //####################show ################################

            if ($page == "show") { //show profile 
                $user = get("*", "users", "WHERE id=" . $_SESSION["id"])->fetch();
    ?>

                <div class="ShowProfile">
                    <div class="container">
                        <h2>Your profile</h2>
                        <div class="profilePicture">

                            <img src="./imgsProfile/<?php echo $user["img"]; ?>" alt="image of profile">
                        </div>
                        <div>Username: <?php echo $user["username"] ?></div>
                        <div>Full name: <?php echo $user["fullname"] ?></div>
                        <div>class : <?php echo $user["class"] ?></div>
                        <div>email: <?php echo $user["email"] ?></div>
                        <div><a href="?page=edit">Edit Profile</a></div>

                    </div>
                </div>
            <?php }

            //####################Edit ################################

            else if ($page == "edit") {
                $user = get("*", "users", "WHERE id=" . $_SESSION["id"])->fetch();
            ?>

                <div class="editProfile">
                    <div class="container">
                        <h2>Change Your profile</h2>
                        <form action="?page=update" method="post" enctype="multipart/form-data">
                            <div class="profilePicture">

                                <img src="./imgsProfile/<?php echo $user["img"]; ?>" alt="image of profile">
                                <br>
                                <input type="file" name="profile" id="">
                            </div>
                            <div> <label>Username</label> <input type="text" name="username" placeholder="username" value="<?php echo $user["username"] ?>"></div>
                            <div> <label>Full name:</label> <input type="text" name="fullname" placeholder="full name" value="<?php echo $user["fullname"] ?>"></div>
                            <div class="password"> <label>password</label> <input id="password" type="password" name="password" placeholder="password" value="<?php echo $user["passwordUser"] ?>"> <span class="show">show</span> </div>
                            <div> <label>email:</label> <input type="text" name="email" placeholder="email" value="<?php echo $user["email"] ?>"></div>
                            <div><input type="submit" name="submit" value="Submit"></div>
                        </form>
                    </div>
                </div>
                <?php
            }

            //####################### update data of user #############################
            else if ($page == "update") {
                if (!empty($_FILES["profile"]["name"])) {
                    $target_dirP = "imgsProfile/";
                    $nameImage = basename(rand(0, 100000000000) . "_" . str_replace('\'', '_', $_FILES["profile"]["name"]));
                    $target_image = $target_dirP . $nameImage;
                    $uOk = 1;

                    if (isset($_POST["submit"])) {
                        $username = $_POST["username"];
                        $fullname = $_POST["fullname"];
                        $password = $_POST["password"];
                        $email = $_POST["email"];
                        $sql = "UPDATE `users` SET `username`='" . $username . "',`fullname`='" . $fullname . "',`email`='" . $email . "',`passwordUser`='" . $password . "',`img`='" . $nameImage . "' WHERE id=" . $_SESSION["id"];


                        //#################################"   Upload file  ###############################"
                        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_image) && insert($sql) > 0) {
                            echo "The profile  has been updated.<br>";
                            $_SESSION["img"] = $nameImage;
                        } else {
                            echo "Sorry, there was an error updating your profile.<br>";
                        }
                    } else {
                        echo "you have not come from form edit ";
                    }
                } else {
                    if (isset($_POST["submit"])) {
                        $username = $_POST["username"];
                        $fullname = $_POST["fullname"];
                        $password = $_POST["password"];
                        $email = $_POST["email"];
                        $sql = "UPDATE `users` SET `username`='" . $username . "',`fullname`='" . $fullname . "',`email`='" . $email . "',`passwordUser`='" . $password . " WHERE id=" . $_SESSION["id"];
                        echo "updated";
                    }
                }

                //####################### modules #########################################

            } else if ($page == "modules") {

                $modules = get("id_module,name,username ", "modules ", "INNER JOIN users ON users.id=modules.prof_id WHERE modules.class=" . $_SESSION["class"])->fetchAll();

                echo "<h1 style='text-align:center;'>Modules</h1>";
                echo "<div class='modules'>";
                if (!empty($modules)) {
                    foreach ($modules as $module) {
                ?>
                        <div class="module">
                            <div class="module-name">Module : <?php echo $module["name"] ?></div>
                            <div class="prof"> Prof : <?php echo $module["username"] ?></div>
                            <a href="pages.php?page=module&id_module=<?php echo $module["id_module"] ?>">See it</a>
                        </div>


                    <?php }
                } else {
                    echo "<h3 style='text-align:center'>il n existe aucun module </h3>";
                }
                echo "</div>";
            }

            //##################### module ###############################################

            else if ($page == "module") {
                if (!isset($_GET["id_module"])) {
                    header("Location:pages.php?page=modules");
                    exit();
                } else {
                    $numModule = $_GET["id_module"];
                    $Module = get('*', "modules", "WHERE id_module=" . $numModule)->fetch();
                    if (!empty($Module)) {
                    ?>
                        <!-- Content of page module-->
                        <div class="module-name">
                            <h1 style="text-align: center;">Module <?php echo $Module["name"] ?></h1>
                        </div>
                        <div class="page_module">
                            <div class="todo">
                                <h4>To do</h4>
                            </div>
                            <div class="cours">
                                <h4>Cours</h4>
                                <?php
                                $cours = get("*", "cours", "WHERE module_id=" . $numModule . " ORDER BY cours.date_pub DESC")->fetchAll();
                                foreach ($cours as $cour) { ?>
                                    <div class="cour">
                                        <img src="./education.png" alt="">
                                        <div class="info">
                                            <form action="./docs/<?php echo $cour["filename"]; ?>" method="get" target="_blank">
                                                <button type="submit"> <a href="#">
                                                        <h3><?php echo $cour["name_cours"]; ?></h3>
                                                    </a></button>
                                            </form>
                                            <span><?php echo $cour["date_pub"]; ?></span>
                                        </div>
                                        <div class="link">
                                            <span>.</span>
                                            <span>.</span>
                                            <span>.</span>
                                        </div>
                                    </div>

                                <?php }
                                ?>
                            </div>
                        </div>
                    <?php }
                }
            }

            //##################" personnes ################################

            else if ($page == "Personnes") {
                if ($_SESSION["statut"] == 3) {

                    //Get data
                    $profs = get("name,img,id_module,fullname", "modules", "INNER JOIN users ON users.id=modules.prof_id WHERE modules.class=" . $_SESSION["class"])->fetchAll();
                    $students = get("*", "users", "WHERE statut=3 AND class=" . $_SESSION["class"])->fetchAll();
                    ?>
                    <div class="personnes">
                        <div class="container">
                            <div id="Enseignants" class="personnes-grp">
                                <h2>Enseignants <span><?php echo get("DISTINCT username", "users", "WHERE statut=2 AND class LIKE '%" . $_SESSION["class"] . "%'")->rowCount();
                                                        echo "\t teachers"; ?></span></h2>
                                <hr>
                                <div>
                                    <?php
                                    foreach ($profs as $prof) {
                                        echo "<div class='personne'>";
                                        echo "<img src='./imgsProfile/" . $prof["img"] . "' alt='user'>";
                                        echo "<h4>" . $prof["fullname"] . "</h4>";
                                        echo "<h4>Module\t<a href='pages.php?page=module&id_module=" . $prof["id_module"] . "'>" . $prof["name"] . "</a></h4>";

                                        echo "</div>";
                                        echo "<hr>";
                                    }

                                    ?>
                                </div>
                            </div>

                            <div id="Eleves" class="personnes-grp">
                                <h2>Etudiants <span><?php echo get("*", "users", "WHERE statut=3 AND class=" . $_SESSION["class"])->rowCount();
                                                    echo "\t students"; ?></span></h2>
                                <hr>
                                <div>
                                    <?php
                                    foreach ($students as $student) {
                                        echo "<div class='personne'>";
                                        echo "<img src='./imgsProfile/" . $student["img"] . "' alt='user'>";
                                        echo "<h4>" . $student["fullname"] . "</h4>";

                                        echo "</div>";
                                        echo "<hr>";
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else if ($_SESSION["statut"] == 2) {
                    $classes_id = (get('*', 'users', "WHERE statut=2 AND id=" . $_SESSION["id"])->fetch()["class"]);
                    if ($classes_id == NULL) {
                        echo "No classes enregistred";
                    } else {
                        $StudentsOfProf = get("users.*,classes.classname", "users,classes", "WHERE classes.id_class=users.class AND statut=3 AND class IN(" . $classes_id . ") ORDER BY classes.classname")->fetchAll();
                        foreach ($StudentsOfProf as $s) {
                            echo "<h3>" . $s["fullname"] . "</h3><span>" . $s["classname"] . "</span><hr>";
                        }
                    }
                }
            }
            //#################### classes for teachers ##########################################

            else if ($page == "classes") {
                if ($_SESSION["statut"] == 3) {
                    header("Location:index.php");
                    exit();
                }
                $classes_id = (get('*', 'users', "WHERE statut=2 AND id=" . $_SESSION["id"])->fetch()["class"]);
                if ($classes_id == NULL) {
                    echo "No classes enregistred";
                } else {
                    $classes = get("*", "classes", "WHERE id_class IN(" . $classes_id . ")")->fetchAll();
                    echo "<h1 style='text-align:center;'>Modules</h1>";
                    echo "<div class='modules'>";
                    if (!empty($classes)) {
                        foreach ($classes as $class) {
                            // get Module name for this class and this teacher
                            if (get('*', 'modules', 'WHERE prof_id=' . $_SESSION["id"] . " AND class=" . $class["id_class"])->rowCount() > 0) {
                                $module_name = get('*', 'modules', 'WHERE prof_id=' . $_SESSION["id"] . " AND class=" . $class["id_class"])->fetch();
                            } else {
                                $module_name = '';
                            }

                    ?>
                            <div class="module">
                                <div class="module-name">class : <?php echo $class["classname"] ?></div>
                                <div class="prof"> module : <?php if ($module_name != '') {
                                                                echo $module_name["name"];
                                                            }  ?></div>
                                <a href="pages.php?page=class&id_class=<?php echo $class["id_class"] ?>">See it</a>
                            </div>


                        <?php }
                    } else {
                        echo "<h3 style='text-align:center'>il n existe aucun module </h3>";
                    }
                    echo "</div>";
                }
            }

            //################# class #######################################

            else if ($page == "class") {
                if (!isset($_GET["id_class"])) {
                    header("Location:pages.php?page=classes");
                    exit();
                } else {
                    $classes_array = explode(',', $_SESSION["class"]);
                    if (!in_array($_GET["id_class"], $classes_array)) {
                        header("Location:pages.php?page=classes");
                        exit();
                    }
                    // GET DATA ABOUT THIS CLASS
                    $numclass = $_GET["id_class"];
                    $Module = get('*', "modules", "WHERE prof_id=" . $_SESSION["id"] . " AND class=" . $numclass)->fetch();
                    $students = get('*', 'users', 'WHERE statut=3 AND class=' . $numclass);
                    $cours = get("*", "cours", "WHERE class=" . $numclass . " AND prof_id=" . $_SESSION["id"] . " ORDER BY cours.date_pub DESC")->fetchAll();
                    if (!empty($Module)) {
                        ?>
                        <!-- Content of page module-->
                        <div class="module-name">
                            <h1 style="text-align: center;">Module <?php echo $Module["name"] ?></h1>
                        </div>
                        <div class="page_module">
                            <div class="etudiants">
                                <h4>Etudiants</h4>
                                <div>
                                    <?php
                                    foreach ($students as $student) {
                                        echo "<div class='personne'>";
                                        echo "<img src='./imgsProfile/" . $student["img"] . "' alt='user'>";
                                        echo "<h4>" . $student["username"] . "</h4>";

                                        echo "</div>";
                                    }

                                    ?>
                                </div>
                            </div>
                            <div class="cours">

                                <?php
                                echo "<h4>Cours</h4>";
                                echo "<a class='add' href='#addCour'>Add cour</a href='#addCour'>";
                                if (!empty($cours)) {
                                    foreach ($cours as $cour) { ?>
                                        <div class="cour">
                                            <img src="./education.png" alt="">
                                            <div class="info">
                                                <form action="./docs/<?php echo $cour["filename"]; ?>" method="get" target="_blank">
                                                    <button type="submit"> <a href="#">
                                                            <h3><?php echo $cour["name_cours"]; ?></h3>
                                                        </a></button>
                                                </form>
                                                <span><?php echo $cour["date_pub"]; ?></span>
                                            </div>
                                            <div class="link">
                                                <span>.</span>
                                                <span>.</span>
                                                <span>.</span>
                                            </div>
                                        </div>

                                <?php }
                                } else {
                                    echo "aucun fichier a afficher";
                                }

                                ?>
                            </div>

                        </div>
                        <div id="addCour">
                            <form action="pages.php?page=addCour&module_id=<?php echo $Module["id_module"] . "&prof_id=" . $_SESSION["id"] . "&class=" . $Module["class"] ?>" method="post" enctype="multipart/form-data">
                                <input type="file" name="file" id="file">
                                <input type="text" name="name_cours">
                                <br><br>
                                <input type="submit" name="submit" value="Submit">
                            </form>
                        </div>
                        <?php
                    } else {
                        echo "aucun a afficher";
                    }
                }
            }

            //################# addCour #######################################

            else if ($page == "addCour") {
                // The target directory of uploading is uploads
                $target_dir = "docs/";
                $namefile = basename(rand(0, 100000000000) . "_" . str_replace('\'', '_', $_FILES["file"]["name"]));
                $target_file = $target_dir . $namefile;
                $uOk = 1;

                if (isset($_POST["submit"])) {
                    $module_id = $_GET["module_id"];
                    $prof_id = $_GET["prof_id"];
                    $name_cour = $_POST["name_cours"];
                    $sql = "INSERT INTO `cours`( `name_cours`, `filename`, `module_id`, `prof_id`, `class`,`date_pub`) VALUES ('" . $name_cour . "','" . $namefile . "','" . $module_id . "','" . $prof_id . "','" . $_GET["class"] . "',now())";


                    //#################################"   Upload file  ###############################"
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) && insert($sql) > 0) {
                        echo "The file " . basename($_FILES["file"]["name"])
                            . " has been uploaded.<br>";
                    } else {
                        echo "Sorry, there was an error uploading your file.<br>";
                    }
                }
            }

            // ################ Notes of Prof ####################################

            else if ($page == "Notes") {
                if ($_SESSION["statut"] == 3) {
                    header("Location:index.php");
                    exit();
                }
                $classes_id = (get('*', 'users', "WHERE statut=2 AND id=" . $_SESSION["id"])->fetch()["class"]);
                if ($classes_id == NULL) {
                    echo "No classes enregistred";
                } else {
                    $classes = get("*", "classes", "WHERE id_class IN(" . $classes_id . ")")->fetchAll();
                    echo "<h1 style='text-align:center;'>Modules</h1>";
                    echo "<div class='modules'>";
                    if (!empty($classes)) {
                        foreach ($classes as $class) {
                            // get Module name for this class and this teacher
                            if (get('*', 'modules', 'WHERE prof_id=' . $_SESSION["id"] . " AND class=" . $class["id_class"])->rowCount() > 0) {
                                $module_name = get('*', 'modules', 'WHERE prof_id=' . $_SESSION["id"] . " AND class=" . $class["id_class"])->fetch();
                            } else {
                                $module_name = '';
                            }

                        ?>
                            <div class="module">
                                <div class="module-name">class : <?php echo $class["classname"] ?></div>
                                <div class="prof"> module : <?php if ($module_name != '') {
                                                                echo $module_name["name"];
                                                            }  ?></div>
                                <a href="pages.php?page=NotesClassProf&id_class=<?php echo $class["id_class"] ?>">See Notes</a>
                            </div>


                        <?php }
                    } else {
                        echo "<h3 style='text-align:center'>il n existe aucun module </h3>";
                    }
                    echo "</div>";
                }
            }

            //################# Notes of a class specific ################

            else if ($page == "NotesClassProf") {
                if ($_SESSION["statut"] == 3) {
                    header("Location:index.php");
                    exit();
                }
                if (!isset($_GET["id_class"])) {
                    header("Location:pages.php?page=classes");
                    exit();
                } else {
                    $classes_array = explode(',', $_SESSION["class"]);
                    if (!in_array($_GET["id_class"], $classes_array)) {
                        header("Location:pages.php?page=classes");
                        exit();
                    }
                    // GET DATA ABOUT THIS CLASS
                    $numclass = $_GET["id_class"];
                    $Module = get('*', "modules", "WHERE prof_id=" . $_SESSION["id"] . " AND class=" . $numclass)->fetch();
                    $students = get("users.fullname,users.id,notes.note", "users LEFT JOIN notes ON users.id=notes.student AND notes.module= ".$Module["id_module"], "WHERE users.statut=3 AND users.class=" . $numclass);
                    if (!empty($Module)) {
                        ?>
                        <!-- Content of page module-->
                        <div class="module-name">
                            <h1 style="text-align: center;">Module <?php echo $Module["name"] ?></h1>
                        </div>
                        <div class="Table-note">
                            <table>
                                <tr>
                                    <th>name</th>
                                    <th>Note</th>
                                </tr>
                                <?php
                                foreach ($students as $student) {
                                    if ($student["note"] == NULL) {
                                        $note = "none";
                                    } else {
                                        $note = $student["note"];
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $student["fullname"] . "</td>";
                                    echo "<td>
                                            <form class='form-note' method='post' action='?page=ChangeNote'>
                                             <input class='note' type='text' name='note' value=" . $note . ">";
                                    /*echo "<button class='button-note Disable'>Add/Modify</button>";*/
                                    echo "<input type='hidden' name='class' value=".$numclass."> 
                                    <input type='hidden' name='module' value=".$Module["id_module"].">
                                    <input type='hidden' name='student' value=".$student["id"].">
                                    <input type='submit' name='submit' class='button-note Disable' onclick='return confirm('Are you sure?')' vlaue='Enregister'>
                                             </form>
                                        </td>";
                                    echo "</tr>";
                                }

                                ?>
                            </table>
                           
                        </div>

    <?php
                    } else {
                        echo "aucun a afficher";
                    }
                }
            }

            /*################ Change or Add note ##################*/ else if ($page == "ChangeNote") {
               
                if (isset($_POST["submit"]) &&  $_SESSION["statut"] == '2') {
                    if ($_POST["note"]=="none") {
                        echo"null value";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                    $note=$_POST["note"];
                    $class=$_POST["class"];
                    $module=$_POST["module"];
                    $student=$_POST["student"];
                    if(get("*","notes","WHERE class=".$class." AND module=".$module." AND student=".$student)->rowCount()==0){
                        echo insert("INSERT INTO notes VALUES('".$module."','".$class."','".$student."','".$note."')")." note inserted with success";
                    }else{
                        echo insert("UPDATE `notes` SET note='".$note."' WHERE module='".$module."' AND class='".$class."' AND student='".$student."'")." note inserted";
                    }

                }

            }

            //################# Notes for Student ########################
            
            else if($page=="NotesStudent"){
                if($_SESSION["statut"]=="3"){
                    $sumNotes=0;
                    $countModules=0;
                    $datas=get("modules.name,notes.note","modules LEFT JOIN notes ON modules.id_module=notes.module AND notes.student=".$_SESSION["id"],"WHERE modules.class=".$_SESSION["class"]);
                    ?>
                    <div class="Table-note">
                            <table>
                                <tr>
                                    <th>module</th>
                                    <th>Note</th>
                                </tr>
                                <?php
                                foreach ($datas as $student) {
                                    if ($student["note"] == NULL) {
                                        $note = "  _  ";
                                    } else {
                                        $note = $student["note"];
                                        $countModules+=1;
                                        $sumNotes+=$note;
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $student["name"] . "</td>";
                                    echo"<td>".$note."</td>";
                                    // echo "<td>
                                    //         <form class='form-note' method='post' action='?page=ChangeNote'>
                                    //          <input class='note' type='text' name='note' value=" . $note . ">";
                                    // /*echo "<button class='button-note Disable'>Add/Modify</button>";*/
                                    // echo "<input type='hidden' name='class' value=".$numclass."> 
                                    // <input type='hidden' name='module' value=".$Module["id_module"].">
                                    // <input type='hidden' name='student' value=".$student["id"].">
                                    // <input type='submit' name='submit' class='button-note Disable' onclick='return confirm('Are you sure?')' vlaue='Enregister'>
                                    //          </form>
                                    //     </td>";
                                    echo "</tr>";
                                }
                                if($countModules==$datas->rowCount()){
                                    $moyenne=$sumNotes/$countModules;
                                }
                                else{
                                    $moyenne="";
                                }
                                echo"<tr>
                                <td>Moyenne</td>
                                <td>".$moyenne."</td>
                                </tr>";

                                ?>
                            </table>
                           
                        </div>
               <?php }
            }

            //################# Else #######################################

            else {
                echo "<p style='text-align=center;'>Page not Found </p>";
            }
        }
    } ?>
    <script>
        // let buttons = document.querySelectorAll('.note');
        // buttons.forEach(btn => {
        //     btn.addEventListener('click', function handleClick(event) {
        //         console.log(btn);
        //         btn.removeAttribute('disabled');
        //         btn.style.border = "2px solid black";
        //     });
        // });

    </script>
    <script src="file.js"></script>
</body>

</html>