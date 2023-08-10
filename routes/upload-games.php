<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

include_once("../utils/globals.php");
include_once("../utils/validators.php");
include_once("../db/conn.php");

if (!array_key_exists("user_id", $_SESSION) || !array_key_exists("email", $_SESSION)) {
    http_response_code(401);
    die();
}

if (!isset($_POST["game-title"])) {
    $error = array("status" => false, "error" => "Game title wasn't set.");
    $game_title = "";
} else {
    $game_title = $_POST["game-title"];
}

if (!isset($_POST["game-author"])) {
    $error = array("status" => false, "error" => "Game author wasn't set.");
    $game_author = "";
} else {
    $game_author = $_POST["game-author"];
}

// $date = time();

if (!isset($_FILES["game-banner"]["name"])) {
    $photo = "";
} else {
    if (
        is_dir(".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title))) ||
        mkdir(".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title)), 0777, true)
    ) {
        $folder = ".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title));
        $filename = $_FILES["game-banner"]["name"];

        $fname = md5($filename) . rand();
        $location = $folder . DIRECTORY_SEPARATOR . $fname;
        // $location2 = $folder . DIRECTORY_SEPARATOR . $fname;
        $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        $valid_extentions = array("jpg", "jpeg", "png", "gif", "webp", "JPG", "JPEG", "PNG", "GIF", "WEBP");
        if (in_array($imageFileType, $valid_extentions)) {
            if (move_uploaded_file($_FILES["game-banner"]["tmp_name"], $location . "." . $imageFileType)) {
                $photo = $location . "." . $imageFileType;
                $photo_url = "\/uploads/" . str_replace(" ", "-", strtolower($game_title)) . "/" . $fname . "." . $imageFileType;
            } else {
                print_r(ini_get("upload_max_filesize"));
            }
        }
    }
}

if (!isset($_FILES["game-file"]["name"])) {
    $game_file = "";
} else {
    if (
        is_dir(".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title))) ||
        mkdir(".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title)))
    ) {
        $folder = ".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . str_replace(" ", "-", strtolower($game_title));
        $filename = $_FILES["game-file"]["name"];

        $fname = md5($filename) . rand();
        $location = $folder . DIRECTORY_SEPARATOR . $fname;
        // $location2 = $folder . DIRECTORY_SEPARATOR . $fname;
        $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        $valid_extentions = array("zip");
        if (in_array($imageFileType, $valid_extentions)) {
            if (move_uploaded_file($_FILES["game-file"]["tmp_name"], $location . "." . $imageFileType)) {
                $game_file = $location . "." . $imageFileType;
            } else {
                print_r(ini_get("upload_max_filesize"));
            }
        }

        $zip = new ZipArchive;
        $res = $zip->open($game_file);
        if ($res === TRUE) {
            $zip->extractTo(str_replace(".zip", "", $game_file));
            $zip->close();
            $game_folder = "\/uploads/" . str_replace(" ", "-", strtolower($game_title)) . "/" . $fname;
        } else {
            $error = array("status" => false, "error" => "Unzipping failed.");
            echo json_encode($error);
            die();
        }
    }
}

$db = new DbConnection(DSN, USER, PASS);

if (
    isset($game_title) &&
    isset($game_author) &&
    isset($photo_url) &&
    isset($game_folder) &&
    isset($_SESSION["user_id"]) &&
    $db->addGame(
        $game_title,
        $game_author,
        $photo_url,
        $game_folder,
        $_SESSION["user_id"]
    )
) {
    echo json_encode(array("status" => true, "success" => "Successfully added!"));
} else {
    $error = array("status" => false, "error" => "Data or Database error. ");
    echo json_encode($error);
    die();
}
