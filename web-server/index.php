<?php

include 'db.php';
$errors = [];
if (isset($_POST["submitLogin"])) {
    $db = dbObj();

    if ($db->connect_errno) {
        $errors[] = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }


    $_POST = array_filter(array_map("sanitize_item", $_POST), function ($value) {
        return $value !== '';
    });

    //Validation
    $required_fields = ["user_key", "user_pass"];

    if (!array_keys_exists($required_fields, $_POST)) {
        $errors[] = "Please ensure that you have filled the following: " . formatFieldsForError($required_fields);
    }

    if (count($errors) == 0) {
        $username = mysqli_escape_string($db, $_POST["user_key"]);
        $password = $_POST["user_pass"];
        $results = $db->query(" SELECT `id`, `user_key`, `video_channel`, `instruction_channel` 
            FROM `accounts` WHERE `user_key`='{$username}' AND `user_pass`='{$password}'");

        if ($results->num_rows <= 0) {
            $errors[] = "This entered details do not match our records.";
        } else {
            list($r_id, $r_username, $r_video_channel, $r_instruction_channel) = $results->fetch_row();

            session_start();
            $_SESSION["id"] = $r_id;
            $_SESSION["user_key"] = $r_username;
            $_SESSION["video_channel"] = $r_video_channel;
            $_SESSION["instruction_channel"] = $r_instruction_channel;
            header('location: dashboard.php');

        }
    }

}
?>
<html>

<head>
    <title>Login|Bio Pulse</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        body {
            background: #fff;
        }

        .input-field input[type=date]:focus + label,
        .input-field input[type=text]:focus + label,
        .input-field input[type=email]:focus + label,
        .input-field input[type=password]:focus + label {
            color: #e91e63;
        }

        .input-field input[type=date]:focus,
        .input-field input[type=text]:focus,
        .input-field input[type=email]:focus,
        .input-field input[type=password]:focus {
            border-bottom: 2px solid #e91e63;
            box-shadow: none;
        }
    </style>
</head>

<body>
<div class="section"></div>
<main>
    <center>
        <img class="responsive-img" style="width: 150px;" src="health.png"/>
        <div class="section"></div>

        <h5 class="indigo-text">Please login into your Biopulse session</h5>
        <div class="section"></div>

        <div class="container">
            <div class="z-depth-1 grey lighten-4 row"
                 style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;width: 350px;">

                <form class="col s12" method="post">
                    <div class='row'>
                        <div class='col s12'>
                            <?php
                            if (count($errors) > 0) {
                                echo "<ul>";
                                foreach ($errors as $error) {
                                    ?>
                                    <li class="red-text text-accent-3"><?php echo $error; ?></li>
                                    <?php
                                }
                                echo "</ul>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='text' name='user_key' id='username'/>
                            <label for='email'>Enter your user key</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='user_pass' id='password'/>
                            <label for='password'>Enter your user pass</label>
                        </div>
                    </div>

                    <br/>
                    <center>
                        <div class='row'>
                            <button type='submit' name='submitLogin' class='col s12 btn btn-large waves-effect indigo'>
                                Login
                            </button>
                        </div>
                    </center>
                </form>
            </div>
        </div>

    </center>

    <div class="section"></div>
    <div class="section"></div>
</main>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>

</html>
