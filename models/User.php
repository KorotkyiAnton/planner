<?php

namespace Models;

use Logs\LogsControl;
use Validation\Validation;
use function Controllers\setcookie;

class User extends CustomerController implements UserInterface
{
    /**
     * @param string $text
     * @return void
     * add task if user write something in text field
     *if user haven't logged in, he can't use this method
     */
    public function addTask(string $text): void
    {
        LogsControl::startProcess();
        try {
            $text = htmlspecialchars($text, ENT_QUOTES);
            if ($this->login != $_POST["user"]) {
                $text = "(From $this->login. To {$_POST['user']}) " . $text;
            }

            if (Validation::notEmptyText($text)) {
                $statement = $this->db->prepare("SELECT id FROM users WHERE login=?");
                $statement->execute([$_POST["user"]]);
                $receiverId = $statement->fetch()["id"];
                $statement = $this->db->prepare("INSERT INTO records (`text`, receiver_id, author_id, source) VALUES (?, ?, ?, ?); ");
                $statement->execute([$text, $receiverId, $this->userId, $this->addFile()]);
            }
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }

    /**
     * @param string $status
     * @param int $id
     * @return void
     * update the status of task with specified id
     */
    public function updateStatus(string $status, int $id): void
    {
        LogsControl::startProcess();
        try {
            $statement = $this->db->prepare("UPDATE records SET status=? WHERE id=?");
            $statement->execute([$status, $id]);
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }

    /**
     * @param int $id
     * @return void
     * delete task with specified id
     */
    public function deleteTask(int $id): void
    {
        LogsControl::startProcess();
        try {
            $statement = $this->db->prepare("DELETE FROM comments WHERE id_record=? AND author_id=?; DELETE FROM records WHERE id=? AND author_id=?");
            $statement->execute([$id, $this->userId, $id, $this->userId]);
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }

    /**
     * @param string $text
     * @param int $id
     * @return void
     * rewrite task with specified id
     */
    public function rewriteTask(string $text, int $id): void
    {
        LogsControl::startProcess();
        try {
            $statement = $this->db->prepare("UPDATE records SET text=? WHERE id=?");
            $statement->execute([$text, $id]);
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
        header("Location:index.php?page=all_tasks");
    }

    /**
     * @param string $text
     * @param int $id
     * @return void
     * add comment to task with specified id
     */
    public function commentTask(string $text, int $id): void
    {
        LogsControl::startProcess();
        try {
            if (Validation::notEmptyText($text)) {
                $text = "Update ({$_COOKIE['userLogin']}): " . $text . "<br>";
                $statement = $this->db->prepare("INSERT INTO comments (`comment`, id_record, author_id) VALUES (?, ?, ?)");
                $statement->execute([$text, $id, $this->userId]);
                LogsControl::endProcessSuccessfully();
            }
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
            header("Location:index.php?page=all_tasks");
        }
    }

    /**
     * @param string $login
     * @param string $password
     * @return void
     * create session in cookie and update session in DB
     * when user have written his login and password in right way
     */
    public function login(string $login, string $password): void
    {
        LogsControl::startProcess();
        try {
            $statement = $this->db->prepare("SELECT login, password FROM users WHERE login = ?");
            $statement->execute([$login]);
            $profile = $statement->fetch();
            if (password_verify($password, $profile["password"])) {
                setcookie("userLogin", $login);
                $statement = $this->db->prepare("UPDATE users SET session=1 WHERE login=?");
                $statement->execute([$login]);
            }
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }

        header("Location:index.php?page=all_tasks");
    }

    /**
     * @return void
     * destroy session in cookie and update session in DB
     * when user press logOut button
     */
    public function logout(): void
    {
        LogsControl::startProcess();
        try {
            setcookie("userLogin", 0, time() - 1);
            $statement = $this->db->prepare("UPDATE users SET session=0 WHERE login=?");
            $statement->execute([$this->userId]);
            LogsControl::endProcessSuccessfully();
        } catch
        (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
        header("Location:index.php?page=all_tasks");
    }
}