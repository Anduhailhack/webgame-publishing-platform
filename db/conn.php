<?php
class DbConnection
{
    private $PDOObj;

    public function __construct($dsn, $user, $pass)
    {
        $this->PDOObj = new PDO($dsn, $user, $pass);
        $this->PDOObj->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    }

    public function getUser($user = "", $pass = "")
    {
        $prepared = $this->PDOObj->prepare("SELECT * FROM `user` WHERE user = :us AND pass = :ps;");
        $prepared->bindParam(":us", $user, PDO::PARAM_STR);
        $prepared->bindParam(":ps", $pass, PDO::PARAM_STR);

        $prepared->execute();
        $value = $prepared->fetch(PDO::FETCH_ASSOC);

        return $value;
    }

    public function addGame($game_title, $game_author, $photo, $game_file, $user_id)
    {
        $prepared = $this->PDOObj->prepare("INSERT INTO `game` (
            `game_title`, 
            `game_author`, 
            `game_banner`, 
            `game_file`, 
            `user_id`
        ) VALUES (:gt, :ga, :gb, :gf, :ui)");

        $prepared->bindParam(":gt", $game_title, PDO::PARAM_STR);
        $prepared->bindParam(":ga", $game_author, PDO::PARAM_STR);
        $prepared->bindParam(":gb", $photo, PDO::PARAM_STR);
        $prepared->bindParam(":gf", $game_file, PDO::PARAM_STR);
        $prepared->bindParam(":ui", $user_id, PDO::PARAM_STR);
        // $prepared->bindParam(":dt", $date, PDO::PARAM_STR);

        return $prepared->execute();
    }

    public function getAllGames()
    {
        $prepared = $this->PDOObj->prepare("SELECT * FROM `game`;");
        $prepared->execute();

        return $prepared;
    }

    public function getGame($id, $user_id)
    {
        $prepared = $this->PDOObj->prepare("SELECT * FROM `game` WHERE id = :gi AND user_id = :ui;");
        $prepared->bindParam(":gi", $id, PDO::PARAM_STR);
        $prepared->bindParam(":ui", $user_id, PDO::PARAM_STR);
        $prepared->execute();

        return $prepared;
    }

    public function deleteGame($id, $user_id)
    {
        $prepared = $this->PDOObj->prepare("DELETE FROM `game` WHERE `id` = :gi AND `user_id` = :ui;");
        $prepared->bindParam(":gi", $id, PDO::PARAM_STR);
        $prepared->bindParam(":ui", $user_id, PDO::PARAM_STR);

        return $prepared->execute();
    }
}
