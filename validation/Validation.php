<?php

namespace Validation;

use Logs\LogsControl;

class Validation
{
    /**
     * @return bool
     * checks if user have already logged in
     */
    public static function onlineSession(): bool
    {
        if(isset($_COOKIE["userLogin"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * checks if user write login and password to fields
     */
    public static function loginPassword(): bool
    {
        if(isset($_POST["login"]) && isset($_POST["password"])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * checks if user have filled up text field
     */
    public static function recordText(): bool
    {
        if(isset($_POST["note_text"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * checks if user have filled up comment field
     */
    public static function commentText(): bool
    {
        if(isset($_POST["update"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * checks if user is admin
     */
    public static function userIsAdmin(): bool
    {
        if($_COOKIE["userLogin"]=="Admin") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * checks if routing work in right way
     */
    public static function pageSet(): bool
    {
        if(isset($_GET["page"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $txt
     * @return bool
     * checks if text is empty
     */
    public static function notEmptyText(string $txt): bool
    {
        if(!empty($txt)) {
            return true;
        } else {
            return false;
        }
    }
}