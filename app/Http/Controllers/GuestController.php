<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\User;
use Nucleus\Routing\Controller;

class GuestController extends Controller
{

    private function listUsers()
    {
        $users = User::get();

        echo "<h1>Users</h1>";
        foreach($users as $user)
        {       
            echo "<div><a href='/users/{$user->id}'>{$user->last_name}, {$user->first_name}</a></div>";
        }
    }

    public function welcome()
    {
        $this->listUsers();
        die;
    }

    public function user(User $user)
    {
        echo "<h1>{$user->first_name} {$user->last_name}</h1>";
        echo "<div>id: {$user->id}</div>";
        echo "<div>first name: {$user->first_name}</div>";
        echo "<div>last name: {$user->last_name}</div>";
        echo "<div>email: {$user->email}</div>";
        $this->listUsers();
        die;
    }
}