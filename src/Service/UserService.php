<?php

    namespace App\Service;

    class UserService
    {

        public function updateAvatarFile(string $img): void
        {
            file_put_contents('img/userAvatar/' . 'avatar', 'test du service');
        }

    }