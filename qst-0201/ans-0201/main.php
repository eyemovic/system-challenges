<?php

$command = $argv[1];

match ($command) {
    'list-users' => getUsers(),
    'add-user' => addUser($argv[2] ?? null, $argv[3] ?? null, $argv[4] ?? null),
    'delete-user' => deleteUser($argv[2] ?? null),
};

function getUsers()
{
    $db = new SQLite3("../app.db");
    try {
        $stmt = $db->prepare("SELECT id, name, age, password FROM users ORDER BY id");
        $result = $stmt->execute();

        if ($result !== false) {
            while ($userInfo = $result->fetchArray(SQLITE3_ASSOC)) {
                $userInfo['password'] = str_repeat("*", strlen($userInfo['password']));

                echo implode(' ', $userInfo) . "\n";
            }
        }

        echo "ユーザーの一覧表示に成功しました。\n";
    } catch (Exception $e) {
        echo "ユーザーの一覧表示に失敗しました。\n";
    }
    $db->close();
}

function addUser($name, $age, $password)
{
    $db = new SQLite3("../app.db");
    try {
        if (!is_string($name)) {
            throw new Exception('ユーザー名が正しくありません');
        }
        if (!is_numeric($age) || $age < 0) {
            throw new Exception('年齢が正しくありません');
        }
        if (!is_string($password)) {
            throw new Exception('パスワードが正しくありません');
        }

        $stmt = $db->prepare("INSERT INTO users(
            name, age, password
        ) VALUES (
            :name, :age, :password
        )"); 
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
        $stmt->bindValue(':password', $password, SQLITE3_TEXT);

        $res = $stmt->execute();

        echo "ユーザーの追加に成功しました。\n";
    } catch (Exception $e) {
        echo "ユーザーの追加に失敗しました。\n";
    }
    $db->close();
}

function deleteUser($id)
{
    $db = new SQLite3("../app.db");
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        if ($db->changes() < 1) {
            throw new Exception('指定したidのユーザーが存在しません。');
        }

        echo "ユーザーの削除に成功しました。\n";
    } catch (Exception $e) {
        echo "ユーザーの削除に失敗しました。\n";
    }
    $db->close();
}
