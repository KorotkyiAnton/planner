<?php

namespace Models;

interface CustomerControllerInterface
{
    public function allTasks(string $status): \PDOStatement;

    public function statusTask(string $status): \PDOStatement;

    public function showTask(int $id): \PDOStatement;

    public function userList(): \PDOStatement;

    public function addFile(): string;
}