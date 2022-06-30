<?php

namespace Models;

use Migrations\DBSingleton;
use PDO;
use PDOStatement;
use Validation\Validation;

class CustomerController implements CustomerControllerInterface
{
    protected PDO $db;
    protected string $login;
    protected int $userId;

    /**
     * Get PDO from singleton and get login and userId from it
     */
    public function __construct()
    {
        DBSingleton::initiateDb();
        $this->db = DBSingleton::getDb();
        if(Validation::onlineSession()){
            $this->login = $_COOKIE["userLogin"];
            $statement = $this->db->prepare("SELECT id FROM users WHERE login=?");
            $statement->execute([$this->login]);
            $this->userId = $statement->fetch()["id"];
        }
    }

    /**
     * @return string
     * get path to uploaded file from $_FILE and return it
     */
    public function addFile(): string
    {
        $source = "";
        if (isset($_FILES)) {
            foreach ($_FILES as $file) {
                move_uploaded_file($file['tmp_name'], __DIR__ . "/../uploaded/" . $file['name']);
                $source = __DIR__ . "/uploaded/" . $file['name'];
            }
        }
        return $source;
    }

    /**
     * @param string $status
     * @return PDOStatement
     * return set of all tasks
     */
    public function allTasks(string $status): PDOStatement
    {
        $statement = $this->db->prepare("
            SELECT a.id, a.`text`, GROUP_CONCAT(a.`comment` SEPARATOR '<br>') AS `comment`, status, receiver_id, author_id, source FROM
            (SELECT records.id, `text`, '' as `comment`, `status`, receiver_id, author_id, source FROM records
            UNION
            SELECT records.id, `text`, `comment`, `status`, receiver_id, records.author_id, source FROM records INNER JOIN comments ON records.id = id_record) as a
            INNER JOIN users ON (a.receiver_id = users.id OR a.author_id = users.id)
            WHERE ? AND login = ?
            GROUP BY a.id
            ORDER BY a.id DESC
        ");
        $statement->execute([$status, $this->login]);
        return $statement;
    }

    /**
     * @param string $status
     * @return PDOStatement
     * return tasks depending to status
     */
    public function statusTask(string $status): PDOStatement
    {
        $statement = $this->db->prepare("
            SELECT a.id, a.`text`, GROUP_CONCAT(a.`comment` SEPARATOR '<br>') AS `comment`, status, receiver_id, author_id, source FROM
            (SELECT records.id, `text`, '' as `comment`, `status`, receiver_id, author_id, source FROM records
            UNION
            SELECT records.id, `text`, `comment`, `status`, receiver_id, records.author_id, source FROM records INNER JOIN comments ON records.id = id_record) as a
            INNER JOIN users ON (a.receiver_id = users.id OR a.author_id = users.id)
            WHERE `status` = ? AND login = ?
            GROUP BY a.id
            ORDER BY a.id DESC
        ");
        $statement->execute([$status, $this->login]);
        return $statement;
    }

    /**
     * @param int $id
     * @return PDOStatement
     * return record with specified id
     */
    public function showTask(int $id): PDOStatement
    {
        $statement = $this->db->prepare("
            SELECT a.id, a.`text`, GROUP_CONCAT(a.`comment` SEPARATOR '<br>') AS `comment`, status, receiver_id, author_id, source FROM
            (SELECT records.id, `text`, '' as `comment`, `status`, receiver_id, author_id, source FROM records
            UNION
            SELECT records.id, `text`, `comment`, `status`, receiver_id, records.author_id, source FROM records INNER JOIN comments ON records.id = id_record) as a
            INNER JOIN users ON (a.receiver_id = users.id OR a.author_id = users.id)
            WHERE a.`id` = ? AND login = ?
        ");
        $statement->execute([$id, $this->login]);
        return $statement;
    }

    /**
     * @return PDOStatement
     * return list of all users
     */
    public function userList(): PDOStatement
    {
        return $this->db->query("SELECT DISTINCT login FROM users WHERE login != 'Admin'");
    }
}