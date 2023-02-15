<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\User;
use Nucleus\Routing\Controller;

class GuestController extends Controller
{
    public function welcome(User $user)
    {
        echo "hello world<br>";
        \var_dump($user); die;
    }

    public function welcome2(User $user, $id, Download $download)
    {
        echo "hello products<br>";
        \var_dump($download); die;
    }

    public function welcome3(User $user)
    {
        echo "hello user<br>";
        \var_dump($user); die;
    }
}