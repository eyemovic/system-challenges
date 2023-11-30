<?php
// connect SQLite
$db = new SQLite3('../app.db');

function listUsers($db) {
    $result = $db->query('SELECT id, name, age, password FROM users ORDER BY id ASC');
    if ($result) {
        echo "show user list:\n";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            // show user
            echo "{$row['id']} {$row['name']} {$row['age']} " . str_repeat('*', strlen($row['password'])) . "\n";
        }
        echo "Get the list of users successfully.\n";
    } else {
        echo "Get the list of failed users.\n";
    }
}

function addUser($db, $name, $age, $password) {
    var_dump($name);
    $stmt = $db->prepare('INSERT INTO users (name, age, password) VALUES (:name, :age, :password)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);

    $result = $stmt->execute();
    if ($result) {
        echo "Added user successfully.\n";
    } else {
        echo "Adding users failed.\n";
    }
}

function deleteUser($db, $id) {
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if ($result) {
        if ($db->changes() > 0) {
            echo "User deletion successful.\n";
        } else {
            echo "The user to be deleted was not found.\n";
        }
    } else {
        echo "User deletion failed.\n";
    }
}

// Handle requests from the command line
$command = $argv[1] ?? '';

if ($command === 'list-users') {
    listUsers($db);
} elseif ($command === 'add-user') {
    $name = $argv[2] ?? '';
    $age = $argv[3] ?? '';
    $password = $argv[4] ?? '';
    addUser($db, $name, $age, $password);
} elseif ($command === 'delete-user') {
    $id = $argv[2] ?? '';
    deleteUser($db, $id);
} else {
    echo "Invalid order.\n";
}

// close connect
$db->close();
?>
