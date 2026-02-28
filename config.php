<?php
define('USERS_FILE', __DIR__ . '/users.json');

function getUsers() {
    if (!file_exists(USERS_FILE)) return [];
    return json_decode(file_get_contents(USERS_FILE), true) ?? [];
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function findUser($value) {
    foreach (getUsers() as $u) {
        if ($u['username'] === $value || $u['email'] === $value) return $u;
    }
    return null;
}
?>