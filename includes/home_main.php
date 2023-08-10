<link rel="stylesheet" href="./css/main.css">
<main>
    <div class="main-container">
        <div class="uploads-menu">
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" id="upload-form">
                <div class="form-control-container">
                    <h2 class="caption">Admin - Upload Games</h2>
                </div>
                <div class="form-control-container">
                    <label class="form-label" for="game-title">Game title</label>
                    <input type="text" name="game-title" id="game-title" placeholder="Game title" class="form-control-in-txt">
                </div>
                <div class="form-control-container">
                    <label class="form-label" for="game-banner">Game banner</label>
                    <div class="game-banner">
                        <img class="game-banner-preview" />
                        <svg id="game-banner-svg" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>
                                svg {
                                    fill: #41b54d
                                }
                            </style>
                            <path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z" />
                        </svg>
                    </div>
                    <input type="file" name="game-banner" accept="images/png, image/*" id="game-banner-btn" class="hidden">
                </div>
                <div class="form-control-container">
                    <label class="form-label" for="game-author">Game author</label>
                    <input type="text" name="game-author" id="game-author" placeholder="Game author" class="form-control-in-txt">
                </div>
                <div class="form-control-container">
                    <label class="form-label" for="game-file">Game file</label>
                    <input type="file" name="game-file" id="game-file" placeholder="Game file" class="file-chooser-btn" accept="zip,application/zip,application/x-zip,application/x-zip-compressed">
                </div>
                <div class="form-control-container">
                    <input type="submit" name="game-subm" id="game-subm" value="Upload" class="btn">
                </div>
            </form>
        </div>
        <div class="games-menu" style="display: none;">
            <div class="game-list-container">
                <?php

                include_once("./utils/globals.php");
                include_once("./db/conn.php");
                $db = new DbConnection(DSN, USER, PASS);

                $value = $db->getAllGames();
                // if ($value->rowCount() <= 0) {
                //     // var_dump($_SERVER);
                //     echo " -- Empty -- ";
                // }

                while (
                    ($game = $value->fetch(PDO::FETCH_ASSOC))
                ) {
                    echo "<div class='game'>";
                    echo "<ul>";
                    echo "<li><b>Game title: </b>" . $game["game_title"] . "</li>";
                    echo "<li><b>Game author: </b>" . $game["game_author"] . "</li>";
                    echo "<li><b>Release date: </b>" . $game["date"] . "</li>";
                    echo "</ul>";
                    echo "<img src='." . str_replace("\\", "", $game["game_banner"]) . "' />";
                    echo "<div class='actions'>
                            <button onclick=\"window.location='." . str_replace(" ", "/", str_replace("\\", "", $game["game_file"])) . "'\" class='btn'> 
                                Preview 
                            </button> 
                            <button onclick=\"navigator.clipboard.writeText(removeAfterHow(window.location.href, '/main') + '" . str_replace(" ", "/", str_replace("\\", "", $game["game_file"])) . "')\" class='btn'> 
                                Get URL 
                            </button> 
                            <button onclick=\"window.location.search='action=delete&id=" . $game["id"] . "&userId=" . $game["user_id"] . "'\"class='btn btn-danger'> 
                                Delete 
                            </button>
                        </div>";
                    echo "</div>";
                }
                /*
                foreach ($value as $game) {
                    echo "<div class='game'>";
                    echo $game["game_title"];
                    echo $game["game_author"];
                    echo $game["date"];
                    echo $game["game_banner"];
                    echo "</div>";
                }
                */

                if (
                    isset($_GET["action"]) &&
                    isset($_GET["id"]) &&
                    isset($_GET["userId"]) &&
                    !isset($_GET["isConfirmed"])
                ) {
                    echo "
                    <script> 
                        function yesOrNoPrompt() {
                            const result = window.confirm('Do you want to continue?')
                            return result
                        }
                
                        if (yesOrNoPrompt()){
                            window.location.search += '&isConfirmed=yes' 
                        }
                    </script>
                        ";
                }

                if (
                    isset($_GET["action"]) &&
                    isset($_GET["id"]) &&
                    isset($_GET["userId"]) &&
                    isset($_GET["isConfirmed"])
                ) {

                    if ($db->deleteGame($_GET["id"], $_GET["userId"])) {
                        echo "
                            <script> 
                                window.location.search = ''
                                alert('Game successfully deleted!') 
                            </script>
                            ";
                    }
                }
                ?>
            </div>
        </div>
        <div class="settings-menu" style="display: none;">
            Settings menu
        </div>
    </div>
</main>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="./js/main.js"></script>