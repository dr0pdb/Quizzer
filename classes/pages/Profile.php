<?php

class Profile extends BasicPage {

    public function render() {
        $this->setTitle('Profile');

        $user = '';
        $rentals = [];
        $admin = false;
        $student = false;
        $instructor = false;

        $id = $this->getLoginInfo();
        if($id != 0 ){
            $user = User::getUserDetails($id);
            $admin = User::isUserAdmin($id);
            $student = User::isUserStudent($id);
            $instructor = User::isUserInstructor($id);
        }

        Renderer::render("profile.php", [
            'user' => $user,
            'admin' => $admin,
        ]);
    }

}