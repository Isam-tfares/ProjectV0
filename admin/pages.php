<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($_GET["page"])) {
                echo $_GET["page"];
            } else {
                echo "page";
            } ?></title>
    <link rel="stylesheet" href="../css/stylead.css">
</head>

<body>
    <?php if (!isset($_SESSION["usernameAdmin"])) {
        header("Location:index.php");
        exit();
    } else { ?>

        <?php include 'header.php' ?>

        <?php $page = isset($_GET["page"]) ? $_GET["page"] : 'main';

        // Students page############################################
        if ($page == "Students") {
            $classes = selectAll("SELECT * FROM classes")->fetchAll();
            echo "<h1 style='text-align:center;'>List of Students </h1>";
            echo "<div class='List'>";
            foreach ($classes as $c) {
                echo "<hr>";
                echo "<div class='Etudiant-parent'>";
                echo "<a href='?page=StudentsClass&class=".$c["id_class"]."'>".$c["classname"]."</a>";
                echo "</div>";
                echo "<hr>";
            }
            echo "</div>";


            //Students In Specific class

        } else if ($page == "StudentsClass") {
            if (isset($_GET["class"])) {
                if (selectAll("SELECT * FROM classes WHERE id_class=" . $_GET["class"])->rowCount() == 0) {
                    header("Location:?page=Students");
                    exit();
                }
                $studentList = selectAll("SELECT users.*,classes.classname FROM users,classes WHERE classes.id_class=users.class AND statut=3 AND class=".$_GET["class"]." ORDER BY classes.classname")->fetchAll();
                echo "<h1 style='text-align:center;'>List of Students </h1>";
                echo "<div class='List'>";
                if(empty($studentList)){
                    echo "<div class='Etudiant-parent'>";
                    echo"List is empty ";
                    echo"</div>";
                }
                foreach ($studentList as $student) {
                    echo "<hr>";
                    echo "<div class='Etudiant-parent'>";
                    echo "<h4>" . $student['username'] . "<br></h4>";
                    echo "<div> <a class='edit' href='pages.php?page=edit&id=" . $student["id"] . "'>Edit</a>   <a class='delete' href='pages.php?page=DeleteStudent&student=" . $student["id"] . "'>Delete</a></div></div>";
                    echo "<hr>";
                }
                echo "</div>";
                echo "<div class='AddS'>";
                echo "<a class='link-add' href='?page=AddStudent&class=".$_GET["class"]."'>Add Student</a>";
                echo "</div>";
            }
        }

        // Profs PAGE #################################################
        else if ($page == "Profs") {
            $ProfsList = selectAll("SELECT * FROM users WHERE statut=2")->fetchAll();
            echo "<h1 style='text-align:center;'>List of Teachers </h1><div class='List'>";
            foreach ($ProfsList as $Prof) {
                echo "<hr>";
                echo "<div class='Etudiant-parent'>";
                echo "<h4>" . $Prof['username'] . "</h4>";
                echo "<div> <a class='edit' href='pages.php?page=edit&id=" . $Prof["id"] . "'>Edit</a>   <a class='delete' href='pages.php?page=DeleteStudent&student=" . $Prof["id"] . "'>Delete</a></div></div>";
                echo "<hr>";
            }
            echo "</div>";
            echo "<div class='AddS'>";
            echo "<a class='link-add' href='?page=AddProf'>Add Prof</a>";
            echo "</div>";
        }

        // Classes Page ##################################################
        else if ($page == "classes") {
            $classesList = selectAll("SELECT * FROM classes")->fetchAll();
            echo "<h1 style='text-align:center;'>Classes</h1>";
            echo "<div class='classs'>";
            if (!empty($classesList)) {
                foreach ($classesList as $class) {
        ?>
                    <div class="class">
                        <div class="class-name">Name : <?php echo $class["classname"] ?></div>
                        <a class="see" href="pages.php?page=class&id_class=<?php echo $class["id_class"] ?>">See it</a>
                        <a class="delete" onclick="return confirm('Are you sure?')" href="pages.php?page=deleteClass&id_class=<?php echo $class["id_class"] ?>">Delete it</a>
                    </div>

            <?php }
            } else {
                echo "<h3 style='text-align:center'>il n existe aucun class </h3>";
            }
            echo "</div>";
            ?>
            <div class='AddClass'>
                <h1>Add Class</h1>
                <form class='add-form' action='?page=addClass' method='POST'>
                    <label for=''>name of class</label>
                    <input required class='inpt' type='text' name='classname'>
                    <input class='btn' type='submit' value="Add class">
                </form>

            </div>
        <?php }

        // Class Page ##############################################
        else if ($page == "class") {
            $class = selectAll("SELECT * FROM classes WHERE id_class=" . $_GET["id_class"])->fetch(); // infos about the class
            echo "<h1 style='text-align:center;'>" . $class["classname"] . "</h1>"; //name of class
            $modules = selectAll("SELECT modules.*,users.* FROM modules INNER JOIN users ON users.id=modules.prof_id WHERE modules.class=" . $_GET["id_class"]);
            $students = selectAll("SELECT * FROM users WHERE statut=3 AND class=" . $_GET["id_class"]);
            $profs = selectAll("SELECT * FROM users WHERE statut=2 AND class LIKE '%" . $_GET["id_class"] . "%'");
        ?>

            <div class='class-parent'>

                <div class='students'>
                    <h3>Students</h3>
                    <ul>
                        <?php foreach ($students as $student) { ?>
                            <li>
                                <div class="relative"><a onclick="return confirm('Are you sure?')" class="delete-module" href="?page=DeleteStudent&student=<?php echo $student["id"] ?>"><img src="../imgsProfile/remove.png" alt=""></a></div>
                                <?php echo $student["username"] ?>
                            </li>
                        <?php } ?>
                        <li class='link-add'><a class='link-add' href='?page=AddStudent&class=<?php echo $class["id_class"] ?>'>Add Student</a></li>
                    </ul>

                </div>

                <div class='moduless'>
                    <h3>Modules</h3>
                    <div class='grid-container'>
                        <?php foreach ($modules as $module) {
                            $cours = selectAll("SELECT * FROM cours WHERE module_id='" . $module["id_module"] . "' AND prof_id='" . $module["id"] . "'")->rowCount(); ?>
                            <div class='grid-item'>
                                <div class='circle'><?php echo strtoupper($module["name"][0]) ?></div>
                                <div class="relative"><a onclick="return confirm('Are you sure?')" class="delete-module" href="?page=DeleteModule&module=<?php echo $module["id_module"] ?>"><img src="../imgsProfile/remove.png" alt=""></a></div>
                                <h4><?php echo $module["name"] ?></h4>
                                <h5>Prof : <?php echo $module["username"] ?></h5>
                                <h5>Number of Files : <?php echo $cours ?></h5>
                                <a href='?page=module&id_module=<?php echo $module["id_module"] ?>' style='font-size:16px;color:white'>See it</a>
                            </div>
                        <?php } ?>
                    </div>
                    <?php $profs = selectAll("SELECT * from users WHERE statut=2")->fetchAll();
                    ?>
                    <div class='AddModule'>
                        <form class='add-form' action='?class=<?php echo $_GET["id_class"]; ?>&page=addModule' method='POST'>
                            <label for=''>name of module</label>
                            <input required class='inpt' type='text' name='modulename'>
                            <label for=''>name of prof</label>
                            <select name="profname" id="profs">
                                <?php
                                foreach ($profs as $prof) {
                                    echo "<option value=" . $prof["id"] . ">" . $prof['fullname'] . "</option>";
                                }
                                ?>
                            </select>
                            <input class='btn' type='submit' value="Add class">
                        </form>

                    </div>
                </div>
            </div>



            <?php }

        // Delete Student
        else if ($page == "DeleteStudent") {
            if (isset($_GET["student"])) {
                echo selectAll("DELETE FROM users WHERE id=" . $_GET["student"])->rowCount() . " student deleted";
            }
        }

        // Delete Module
        else if ($page == "DeleteModule") {
            if (isset($_GET["module"])) {

                $profIdOfThisModule = selectAll("SELECT * FROM modules WHERE id_module=" . $_GET["module"])->fetch();
                echo selectAll("DELETE FROM modules WHERE id_module=" . $_GET["module"])->rowCount() . " module deleted";

                if (selectAll("SELECT * FROM modules WHERE prof_id=" . $profIdOfThisModule["prof_id"] . " AND class=" . $profIdOfThisModule["class"])->rowCount() == 0) {
                    $classesOfProf = explode(",", selectAll("SELECT class FROM users WHERE id=" . $profIdOfThisModule["prof_id"])->fetch()["class"]);

                    if (($key = array_search($profIdOfThisModule["class"], $classesOfProf)) !== false) {

                        unset($classesOfProf[$key]);
                    }
                    $newClass = implode(",", $classesOfProf);
                    selectAll("UPDATE users SET class='" . $newClass . "' WHERE users.id=" . $profIdOfThisModule["prof_id"]);
                }
            }
        }

        // Delete Cours
        else if ($page == "Deletecours") {
            if (isset($_GET["cour"])) {
                echo selectAll("DELETE FROM cours WHERE id=" . $_GET["cour"])->rowCount() . " cour deleted";
            }
        }


        // Module Page
        else if ($page == "module") {
            if (!isset($_GET["id_module"])) {
                header("Location:pages.php?page=modules");
                exit();
            } else {
                $numModule = $_GET["id_module"];
                $Module = selectAll("SELECT * FROM modules WHERE id_module=" . $numModule)->fetch();
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
                            $cours = selectAll("SELECT * FROM cours WHERE module_id=" . $numModule . " ORDER BY cours.date_pub DESC")->fetchAll();
                            foreach ($cours as $cour) { ?>
                                <div class="cour">
                                    <div class="relative"><a onclick="return confirm('Are you sure?')" class="delete-module" href="?page=Deletecours&cour=<?php echo $cour["id"]; ?>"><img src="../imgsProfile/remove.png" alt=""></a></div>
                                    <img src="../education.png" alt="">
                                    <div class="info">
                                        <form action="../docs/<?php echo $cour["filename"]; ?>" method="get" target="_blank">
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


        // Add Student 
        else if ($page == "AddStudent") {
            $classes = selectAll("SELECT * FROM classes");
            ?>
            <div class="editProfile">
                <div class="container">
                    <h2>Add Student</h2>
                    <form action="?page=AddToDataBase" method="post">
                        <div> <label>Username</label> <input type="text" name="username" placeholder="username"></div>
                        <div> <label>Full name:</label> <input type="text" name="fullname" placeholder="full name"></div>
                        <div class="password"> <label>password</label> <input id="password" type="password" name="password" placeholder="password"> <span class="show">show</span> </div>
                        <div> <label>email:</label> <input type="text" name="email" placeholder="email"></div>
                        <div><label for="">class: </label><select name="class" id="">
                                <?php
                                foreach ($classes as $class) { ?>
                                    <option value="<?php echo $class["id_class"] ?>" <?php if (isset($_GET["class"]) and $_GET["class"] == $class["id_class"]) {
                                                                                            echo "selected";
                                                                                        } ?>> <?php echo $class['classname'] ?></option>
                                <?php }
                                ?>
                            </select> </div>
                        <div><input type="submit" name="submit" value="Submit"></div>
                    </form>
                </div>
            </div>
        <?php }

        // Add Student to database
        else if ($page == "AddToDataBase") {
            if (isset($_POST["submit"])) {
                $username = $_POST["username"];
                $fullname = $_POST["fullname"];
                $password = $_POST["password"];
                $email = $_POST["email"];
                $class = $_POST["class"];
                // Add Statement SQL
                $sql = "INSERT INTO users(username,fullname,email,passwordUser,class) VALUES('" . $username . "','" . $fullname . "','" . $email . "','" . $password . "','" . $class . "')";
                if (selectAll($sql)->rowCount() > 0) {
                    echo "INSERTED SUCCESFULLY";
                };
            }
        }

        // Add Prof 
        else if ($page == "AddProf") {
            // $classes = selectAll("SELECT * FROM classes");
        ?>
            <div class="editProfile">
                <div class="container">
                    <h2>Add Prof</h2>
                    <form action="?page=AddToDataBaseProf" method="post">
                        <div> <label>Username</label> <input type="text" name="username" placeholder="username"></div>
                        <div> <label>Full name:</label> <input type="text" name="fullname" placeholder="full name"></div>
                        <div class="password"> <label>password</label> <input id="password" type="password" name="password" placeholder="password"> <span class="show">show</span> </div>
                        <div> <label>email:</label> <input type="text" name="email" placeholder="email"></div>
                        <div><input type="submit" name="submit" value="Submit"></div>
                    </form>
                </div>
            </div>
        <?php }

        // Add Prof to database
        else if ($page == "AddToDataBaseProf") {
            if (isset($_POST["submit"])) {
                $username = $_POST["username"];
                $fullname = $_POST["fullname"];
                $password = $_POST["password"];
                $email = $_POST["email"];
                // Add Statement SQL
                $sql = "INSERT INTO users(username,fullname,email,passwordUser,statut) VALUES('" . $username . "','" . $fullname . "','" . $email . "','" . $password . "','2')";
                if (selectAll($sql)->rowCount() > 0) {
                    echo "INSERTED SUCCESFULLY";
                };
            }
        }
        // Add class PAGE #########################################


        else if ($page == "addClass") {

            //check if there are a form received
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                header("Location:pages.php?page=classes");
                exit();
            }
            $name = $_POST["classname"];
            if (selectAll("SELECT * FROM classes WHERE classname='" . $name . "'")->rowCount() == 0) {
                $stmt = selectAll("INSERT INTO classes(classname) VALUES('" . $name . "')");
                echo $stmt->rowCount();
            } else {
                echo "name exist already";
            }

            //Add Module Page
        } else if ($page == "addModule") {

            //check if there are a form received
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                header("Location:pages.php?page=classes");
                exit();
            }
            $modulename = $_POST["modulename"];
            $profname = $_POST["profname"];
            $class = $_GET["class"];
            if (selectAll("SELECT * FROM modules WHERE name='" . $modulename . "' AND class='" . $class . "'")->rowCount() == 0) {
                $classesOfProf = explode(",", selectAll("SELECT class FROM users WHERE id=" . $profname)->fetch()["class"]);
                if (!in_array($class, $classesOfProf)) {
                    array_push($classesOfProf, $class);
                }
                $newClass = implode(",", $classesOfProf);
                selectAll("UPDATE users SET class='" . $newClass . "' WHERE users.id=" . $profname);
                $stmt = selectAll("INSERT INTO modules (name,class,prof_id) VALUES ('" . $modulename . "','" . $class . "','" . $profname . "')");
                echo $stmt->rowCount() . " modules added";
            } else {
                echo "module name exist already";
            }
        }

        // DELETE CLass Page #######################################
        else if ($page == "deleteClass") {


            $id = $_GET["id_class"];
            if (selectAll("DELETE FROM `classes` WHERE id_class='" . $id . "'")->rowCount() > 0) {
                echo "Deleted with succes";
                if (false) { // dlete all students in class
                    echo selectAll("DELETE FROM `users` WHERE statut=3 AND class='" . $id . "'")->rowCount() . " students deleted";
                }
            } else {
                echo "There are a problem please retry";
            }
        }

        // EDIT PAGE ##############################################

        else if ($page == "edit") { ?>

            <h2 style="text-align: center;">edit</h2>
            <?php
            $student = selectAll("SELECT * FROM users WHERE id=" . $_GET["id"])->fetch();
            ?>
            <!--create the form to modify infos of users-->
            <form class="edit_form" action=<?php echo "pages.php?page=update&id=" . $_GET['id'] ?> method="POST">
                <label for="">Username</label>
                <input required class="inpt" type="text" name="username" value=<?php echo $student["username"] ?>>
                <label for="">fullname</label>
                <input required class="inpt" type="text" name="fullname" value=<?php echo $student["fullname"] ?>>
                <label for="">email</label>
                <input required class="inpt" type="email" name="email" value=<?php echo $student["email"] ?>>
                <label for="">statut</label>
                <input required class="inpt" type="statut" name="statut" value=<?php echo $student["statut"] ?>>
                <label for="">password</label>
                <input required class="inpt" type="text" name="password" value=<?php echo $student["passwordUser"] ?>>
                <input class='btn' type="submit">
            </form>
            <?php if (isset($_GET["error"])) {
                echo "<span class='error-edit'>" . $_GET["error"] . "</span>";
            } ?>


        <?php }
        // DELETE  PAGE ##############################################
        else if ($page == "delete") {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                header("Location:dashboard.php");
                exit();
            }
            $dsn = "mysql:host=localhost;dbname=project";
            $user_host_name = "root";
            $password_host = "";
            $connect = new PDO($dsn, $user_host_name, $password_host);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connect->prepare("DELETE FROM `users` WHERE `users`.`id` = " . $_GET["id"]);
            $stmt->execute();
            echo " deleted with succes";

            // UPDATE PAGE ##############################################

        } else if ($page == "update") {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                header("Location:dashboard.php");
                exit();
            }
            $username = $_POST["username"];
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $statut = $_POST["statut"];
            $password = $_POST["password"];
            if (!(empty($username) || empty($email) || empty($fullname) || empty($statut) || empty($password))) {
                $dsn = "mysql:host=localhost;dbname=project";
                $user_host_name = "root";
                $password_host = "";
                $connect = new PDO($dsn, $user_host_name, $password_host);
                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $connect->prepare("UPDATE `users` SET `username`='" . $username . "',`fullname`='" . $fullname . "',`email`='" . $email . "',`passwordUser`='" . $password . "',`statut`='" . $statut . "',`class`='3' WHERE id=" . $_GET["id"]);
                $stmt->execute();
                echo " update effected";
            } else {
                header("Location:pages.php?page=edit&error=there is a champ empty&id=" . $_GET["id"]);
                exit();
            }
        }

        //  ELSE PAGE 
        else {
            echo "koun khraiti hssn";
        }
        ?>






    <?php }
    ?>

    <?php
    function selectAll($code)
    {
        $dsn = "mysql:host=localhost;dbname=project";
        $user_host_name = "root";
        $password_host = "";
        $connect = new PDO($dsn, $user_host_name, $password_host);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $connect->prepare($code);
        $stmt->execute();
        return $stmt;
    }
    ?>
    <script src="../file.js"></script>
</body>

</html>