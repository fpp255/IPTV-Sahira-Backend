<?php

function can($roles)
{
    $userRole = session()->get('role');

    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }

    return $userRole === $roles;
}
