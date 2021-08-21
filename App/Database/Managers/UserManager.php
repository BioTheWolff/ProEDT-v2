<?php


namespace App\Database\Managers;

use function App\e;

class UserManager extends AbstractManager
{

    const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;

    public function fetch_user(String $reference)
    {
        $statement = $this->connection->prepare(
            "SELECT *
                        FROM users 
                        WHERE 
                            username = :reference"
        );

        $e = e($reference);
        $statement->bindParam(':reference', $e);

        $statement->execute();
        return $statement->fetch();
    }

//    public function fetch_permissions(string $canonical)
//    {
//        $statement = $this->connection->prepare(
//            "SELECT permissions
//                        FROM \"userRoles\" ur
//                        JOIN roles r ON r.name = ur.role_name
//                        WHERE user_canonical = :reference"
//        );
//
//        $e = e($canonical);
//        $statement->bindParam(':reference', $e);
//
//        $statement->execute();
//
//        $result = $statement->fetchAll(\PDO::FETCH_NUM);
//        $arr = [];
//        foreach ($result as $row) {
//            $arr[] = (int) $row[0];
//        }
//
//        return $arr;
//    }


    public function is_username_available(string $username): bool {
        $statement = $this->connection->prepare(
            "SELECT * FROM users 
                        WHERE username = :reference"
        );

        $escaped_username = e($username);

        $statement->bindParam(':reference', $escaped_username);

        $statement->execute();
        return empty($statement->fetchAll());
    }


//    public function update_account_details(string $canonical_username, string $new_username, string $new_email): bool {
//        if (!$this->is_username_available($canonical_username, $new_username)) return false;
//        if (!$this->is_email_available($canonical_username, $new_email)) return false;
//
//        $statement = $this->connection->prepare(
//            "UPDATE users SET username = :new_username, email = :new_email WHERE canonical_username = :canonical"
//        );
//
//        $escaped_username = e($new_username);
//        $escaped_email = e($new_email);
//        $escaped_canonical = e($canonical_username);
//
//        $statement->bindParam(':new_username', $escaped_username);
//        $statement->bindParam(':new_email', $escaped_email);
//        $statement->bindParam(':canonical', $escaped_canonical);
//
//        $statement->execute();
//        return $statement->rowCount() > 0;
//    }

    public function update_password(string $username, string $current, string $new): bool {
        $user = $this->fetch_user($username);

        if (!password_verify($current, $user->password)) return false;

        $statement = $this->connection->prepare("UPDATE users SET password = :password WHERE username = :ref");

        $escaped_username = e($username);
        $hashed_password = password_hash($new, self::PASSWORD_ALGORITHM);

        $statement->bindParam(':ref', $escaped_username);
        $statement->bindParam(':password', $hashed_password);

        $statement->execute();
        return $statement->rowCount() > 0;
    }

}