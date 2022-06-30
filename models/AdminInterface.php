<?php

namespace Models;

interface AdminInterface
{
    /***
     * add data to database (table users)
     */
    public function registration(string $login, string $password): void;

    /***
     * remove data from database (table users)
     */
    public function deleteUser(): void;
}