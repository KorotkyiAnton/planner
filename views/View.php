<?php

namespace Views;

use PDOStatement;

class View
{
    /***
     * @param PDOStatement $users
     * @return void
     * print layout for addTask method
     * user can choose receiver of task
     */
    public static function addTask(\PDOStatement $users): void
    {
        echo "<div class='block_main' id='block_main'>
            <form class='add_notes' action='../public/index.php?page=add_task' method='post' enctype='multipart/form-data'>
                <select name='user' class='smallItem'>";
                    while ($data = $users->fetch()){
                        echo "<option value='{$data['login']}'>{$data['login']}</option>";
                    }
                echo "</select>";
                echo "<textarea class='note_text' placeholder='Добавь запись' name='note_text'></textarea><br>
                <input type='file' class='smallItem' name='file'><br>
                <input class='add_note' type='submit' value='Добавить запись'>
            </form>
        </div>";
    }

    /**
     * @param PDOStatement $tasks
     * @return void
     * print layout for *Tasks methods
     * user can see added file
     * user can see current status of task
     */
    public static function tasks(PDOStatement $tasks): void
    {
        echo "<div class='block_main' id='block_main'>";
        while ($data = $tasks->fetch()) {
            $source = "../uploaded/".explode("/uploaded/", $data['source'])[1];
            echo "
            <div class='record_area' ondblclick='displayOptions()'>
                <div class='records'>";
            if(explode("/uploaded/", $data['source'])[1]!=""){
                echo "<a href='{$source}' target='_blank' style='color: white; text-decoration: none'>Документ</a><br><br>";
            }

            if($data["status"]=="inProgress") {
                echo "(🞂) ";
            } elseif($data["status"]=="stopped") {
                echo "(⏸) ";
            } else {
                echo "(✓) ";
            }
                echo htmlspecialchars_decode($data['text']) .
                "<hr>" .
                htmlspecialchars_decode($data['comment']) .
                "</div>
                <div class='options' style='display: none'>
                    <a class='op_menu' href='index.php?page=rewriteTask&id=" . $data['id'] . "'>🖊</a>
                    <a class='op_menu' href='index.php?page=rewriteTask&id=" . $data['id'] . "'>💬</a>
                    <a class='op_menu' href='index.php?page=updateStatus&status=inProgress&id=" . $data['id'] . "'>🞂</a>
                    <a class='op_menu' href='index.php?page=updateStatus&status=stopped&id=" . $data['id'] . "'>⏸</a>
                    <a class='op_menu' href='index.php?page=updateStatus&status=done&id=" . $data['id'] . "'>✓</a>
                    <a class='op_menu' href='index.php?page=deleteTask&status=deleted&id=" . $data['id'] . "'>🗑</a>
                </div>
            </div>";
        }
        echo "</div>";
    }

    /**
     * @param PDOStatement|null $text
     * @return void
     * print layout for rewrite and comment methods
     * user can add new task text in field
     * user can add comment text in field
     */
    public static function rewrite(?\PDOStatement $text): void
    {
        $data = $text->fetch();
        echo "<div class='block_main' id='block_main'>
            <form class='add_notes' action='index.php?page=rewriteTask&id=".$data['id']."' method='post'>
                <textarea class='note_text' placeholder='Добавь запись' name='note_text'>".htmlspecialchars_decode($data['text']) ."</textarea><br>
                <textarea class='update' placeholder='Добавь комментарий' name='update'></textarea><br>
                <input class='add_note' type='submit' value='Добавить запись'>
            </form>
        </div>";
    }

    /**
     * @return void
     * print layout for login and registration methods
     * user can fill up login and password fields
     */
    public static function authorization(): void
    {
        echo "
        <div class='block_main' id='block_main'>
            <form action='' method='post' class='add_notes'>
                <input class='update login' type='text' name='login' placeholder='Логин'>
                <input class='update password' type='text' name='password' placeholder='Пароль'>                
                <input class='add_note authorize' type='submit' value='Подтвердить'>
            </form>
        </div>
        ";
    }

    /**
     * @param string $users
     * @return void
     * print layout for deleteUser method
     * admin can choose user to delete from select list
     */
    public static function deleteUser(string $users): void
    {
        echo "<div class='block_main' id='block_main'>
            <form class='add_notes' action='../public/index.php?page=deleteUser' method='post' enctype='multipart/form-data'>
                <select name='deleteUser' class='smallItem'>";
                    while ($data = $users->fetch()){
                        echo "<option value='{$data['login']}'>{$data['login']}</option>";
                    }
                    echo "
                </select>
                <input class='add_note' type='submit' value='Удалить пользователя'>
            </form>
        </div>";
    }
}