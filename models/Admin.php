<?php

namespace Models;

use Logs\LogsControl;

class Admin extends CustomerController implements AdminInterface
{
    /**
     * @param string $login
     * @param string $password
     * @return void
     * register new user
     */
    public function registration(string $login, string $password): void
    {
        LogsControl::startProcess();
        try {
            $statement = $this->db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
            $statement->execute([$login, $password]);
            LogsControl::endProcessSuccessfully();
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }

    /**
     * @return void
     * delete user account if it exists
     */
    public function deleteUser(): void
    {
        LogsControl::startProcess();
        try {
            if (isset($_POST["deleteUser"])) {
                $statement = $this->db->prepare("DELETE FROM users WHERE login=?");
                $statement->execute([$_POST["deleteUser"]]);
            }
        } catch
        (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }
}