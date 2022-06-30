<?php

namespace Models;

interface UserInterface
{
    /***
     * add data to database (table records)
     */
    public function addTask(string $text): void;

    /***
     * update data in database (table records)
     */
    public function updateStatus(string $status, int $id): void;

    /***
     * remove data from database (table records)
     */
    public function deleteTask(int $id): void;

    /***
     * update data in database (table records)
     */
    public function rewriteTask(string $text, int $id): void;

    /***
     * add data to database (table comments)
     */
    public function commentTask(string $text, int $id): void;

    /***
     * make checkout and update data in database (table users)
     */
    public function login(string $login, string $password): void;

    /***
     * update data in database (table users)
     */
    public function logout(): void;
}