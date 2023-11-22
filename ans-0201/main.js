const sqlite3 = require('sqlite3').verbose();
const md5 = require('md5');

const DB_PATH = '../qst-0201/app.db';

const usage = () => {
    console.log('Usage:');
    console.log('  node main.js list-users');
    console.log('  node main.js add-user <name> <age> <password>');
    console.log('  node main.js delete-user <user_id>');
}

const getUsers = async () => {
    const db = new sqlite3.Database(DB_PATH);

    const query = 'SELECT * FROM users ORDER BY id';

    return new Promise((resolve, reject) => {
        db.all(query, (err, rows) => {
            db.close();

            if (err) {
                reject(err);
                return;
            }

            resolve(rows);
        });
    });
}

const addUser = async (name, age, password) => {
    const db = new sqlite3.Database(DB_PATH);

    const hashedPassword = md5(password);

    const query = 'INSERT INTO users (name, age, password) VALUES (?, ?, ?)';

    return new Promise((resolve, reject) => {
        db.run(query, [name, age, hashedPassword], function (err) {
            db.close();

            if (err) {
                reject(err);
                return;
            }

            resolve(this.lastID);
        });
    });
}

const deleteUser = (userId) => {
    const db = new sqlite3.Database(DB_PATH);

    const query = 'DELETE FROM users WHERE id = ?';

    return new Promise((resolve, reject) => {
        db.run(query, userId, function (err) {
            db.close();

            if (err) {
                reject(err);
                return;
            }

            resolve(this.changes);
        });
    });
};

const hidePassword = (password) => '*'.repeat(password.length);

const executeListUsers = async () => {
    try {
        const users = await getUsers();

        users.forEach(user => {
            console.log(`${user.id} ${user.name} ${user.age} ${hidePassword(user.password)}`);
        });
        console.log('ユーザーの一覧表示に成功しました。');
    } catch (error) {
        console.error('ユーザーの一覧表示に失敗しました。');
    }
}

const executeAddUser = async (name, age, password) => {
    try {
        await addUser(name, age, password);
        console.log('ユーザーの追加に成功しました。');
    } catch (error) {
        console.error('ユーザーの追加に失敗しました。');
    }
}

const executeDeleteUser = async (userId) => {
    try {
        const changes = await deleteUser(userId);

        if (changes === 0) {
            throw new Error('User does not exist!');
        }

        console.log('ユーザーの削除に成功しました。');
    } catch (error) {
        console.error('ユーザーの削除に失敗しました。');
    }
}

const main = async () => {
    const command = process.argv[2];

    if (command === 'list-users') {
        await executeListUsers();
    } else if (command === 'add-user' && process.argv[3] && process.argv[4] && process.argv[5]) {
        const name = process.argv[3];
        const age = process.argv[4];
        const password = process.argv[5];

        await executeAddUser(name, age, password);
    } else if (command === 'delete-user' && process.argv[3]) {
        const userId = process.argv[3];

        await executeDeleteUser(userId);
    } else {
        usage();
    }
}

main();