<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.Role.{id}', function ($user, $id) {
    // for employee
    // dd($user);
    // return (int) $user->id === (int) $id;
    return true;
});

Broadcast::channel('App.Models.Users.{id}', function ($user, $id) {
    // for supplier and company
    return (int) $user->id === (int) $id;
});

