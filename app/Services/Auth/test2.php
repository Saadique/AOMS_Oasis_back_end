<?php


namespace App\Services\Auth;


use App\Role;

class test2
{
    public function getAuthorizedViews($roleId)
    {
        $role = Role::findOrFail($roleId);
        $views = $role->views;

    }

}
