<?php

require_once('../classes/db/UserService.php');
require_once('../classes/db/QuizService.php');
require_once('../classes/Renderer.php');
require_once('../classes/Utils.php');

abstract class BasicPage {
    private $loginInfo;
    private $isAdmin;
    private $isStudent;
    private $isInstructor;

    public function __construct(){
        session_start();

        $this->refreshStatus();
    }

    public function refreshStatus() {
        $this->loginInfo = Utils::getLoggedIn();
        $this->isAdmin = User::isUserAdmin($this->loginInfo);
        $this->isStudent = User::isUserStudent($this->loginInfo);
        $this->isInstructor = User::isUserInstructor($this->loginInfo);
        Renderer::inject('loginInfo', $this->loginInfo);
        Renderer::inject('isAdmin', $this->isAdmin);
        Renderer::inject('isStudent', $this->isStudent);
        Renderer::inject('isInstructor', $this->isInstructor);
    }

    public function setTitle($title) {
        Renderer::inject('title', $title);
    }

    public function getLoginInfo() {
        return $this->loginInfo;
    }

    public function isAdmin(){
        return $this->isAdmin;
    }

    public function isStudent(){
        return $this->isStudent;
    }

    public function isInstructor(){
        return $this->isInstructor;
    }

    public abstract function render();

}