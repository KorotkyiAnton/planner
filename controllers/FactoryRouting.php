<?php

namespace Controllers;

use Models\Admin;
use Models\User;
use Validation\Validation;
use Views\View;

final class FactoryRouting
{
    /**
     * @param string $id
     * @return void
     * make routing on site: load views, start methods from models, connect models to DB
     */
    static public function Routing(string $id): void
    {
        $user = new User(); //make object for User model
        $admin = new Admin(); //make object for Admin model

        switch ($id) {
            case "add_task":
                if(Validation::onlineSession()) {
                    View::addTask($user->userList());
                    if (Validation::recordText()) {
                        $user->addTask($_POST["note_text"]);
                    }
                }
                break;

            case "all_tasks":
                if(Validation::onlineSession()) {
                    $tasks = $user->allTasks(1);
                    View::tasks($tasks);
                }
                break;

            case "in_progress":
                if(Validation::onlineSession()) {
                    $tasks = $user->statusTask("inProgress");
                    View::tasks($tasks);
                }
                break;

            case "stopped":
                if(Validation::onlineSession()) {
                    $tasks = $user->statusTask("stopped");
                    View::tasks($tasks);
                }
                break;

            case "done":
                if(Validation::onlineSession()) {
                    $tasks = $user->statusTask("done");
                    View::tasks($tasks);
                }
                break;

            case "updateStatus":
                if(Validation::onlineSession()) {
                    $user->updateStatus($_GET["status"], $_GET["id"]);
                    $tasks = $user->allTasks(1);
                    View::tasks($tasks);
                }
                break;

            case "deleteTask":
                if(Validation::onlineSession()) {
                    $user->deleteTask($_GET["id"]);
                    $tasks = $user->allTasks(1);
                    View::tasks($tasks);
                }
                break;

            case "rewriteTask":
                if(Validation::onlineSession()) {
                    $text = $user->showTask($_GET["id"]);
                    View::rewrite($text); //one view and models' method to present rewriting and commenting
                    if (Validation::recordText()) { //if user write something in rewrite field
                        $user->rewriteTask(htmlspecialchars($_POST["note_text"], ENT_QUOTES), $_GET["id"]);
                    }
                    if (Validation::commentText()) { //if user write something in comment field
                        $user->commentTask(htmlspecialchars($_POST["update"], ENT_QUOTES), $_GET["id"]);
                    }
                }
                break;

            case "login":
                if(Validation::loginPassword()) {
                    $user->login($_POST["login"], $_POST["password"]);
                }
                View::authorization();
                break;

            case "register":
                if(Validation::loginPassword() && Validation::onlineSession()) {
                    $admin->registration($_POST["login"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                }
                View::authorization();
                break;

            case "logout":
                if(Validation::onlineSession()) {
                    $user->logout();
                    $tasks = $user->allTasks(1);
                    View::tasks($tasks);
                }
                break;

            case "deleteUser":
                $admin->deleteUser();
                View::deleteUser($user->userList());
                break;
        }
    }
}