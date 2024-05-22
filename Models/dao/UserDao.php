<?php

namespace App\Models\dao;

use App\Models\entities\User;
use PDO;
use PDOException;

class UserDao extends Db
{


    public function getUsers(): array
    {
        $stmt = $this->instance->prepare("SELECT * FROM users");
        $res = $stmt->execute();

        if ($res) {
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $this->createObjectFromFields($row);
            }
            return $users;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function register($pseudo, $email, $password, $ip)
    {

        // on vérifie si le pseudo ou mail  existe déjà en base de données
        $stmt = $this->instance->prepare("SELECT id FROM users WHERE pseudo = :pseudo OR email = :email");
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->bindParam("pseudo", $pseudo, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // un utilisateur avec le même pseudo ou mail  existe
            $_SESSION["error"] = "L'email ou pseudo déjà existant.";
            header("location: ../../public/user/formRegister");
        } else {

            // Le pseudo et mail sont unique, on peut enregistrer la demande
            $stmt = $this->instance->prepare("INSERT INTO `users` (`email`, `pseudo`, `password`, `ip`) VALUES (:email, :pseudo, '$password', :ip)");
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->bindParam("pseudo", $pseudo, PDO::PARAM_STR);
            $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
            $res = $stmt->execute();
            $_SESSION["message"] = "Enregistrement réussi.";
            header("location: ../../");

            if (!$res) {
                throw new PDOException($stmt->errorInfo()[2]);
            }
        }


    }

    public function login($email, $password)
    {
        // on vérifie si le mail existe déjà en base de données
        $stmt = $this->instance->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['error'] = "";
                $_SESSION['user'] = [
                    "id" => $user["id"],
                    "email" => $user["email"],
                    "pseudo" => $user["pseudo"],
                    "role" => $user["role"],
                    "ip" => $user["ip"]
                ];
                return $this->createObjectFromFields($user);
            } else {
                $_SESSION['error'] = "Login et/ou mot de passe incorrect";
                header("location: ../user/formLogin");
            }
        } else {
            $_SESSION['error'] = "Login et/ou mot de passe incorrect";
            header("location: ../user/formLogin");
        }
    }

    public function emailExist($email)
    {
        // on vérifie si le mail existe déjà en base de données
        $stmt = $this->instance->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
              return true;
        } else {
            return false;
        }
    }


    public function resetPassword($email, $hashedPassword)
    {
        // on vérifie si le mail existe déjà en base de données
        $stmt = $this->instance->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $this->instance->prepare("UPDATE users SET password = '$hashedPassword' WHERE email = :email");
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->execute();
            return;
        } else {
            $_SESSION['error'] = "Email incorrect ou inconnu";
            header("location: ../user/formForgotPassword");
        }
    }
    public function resetPasswordWithForm($idUser, $actualPassword, $hashedPassword)
    {
        // on vérifie si l'user existe déjà en base de données
        $stmt = $this->instance->prepare("SELECT password FROM users WHERE id = :idUser");
        $stmt->bindParam("idUser", $idUser, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($actualPassword, $user['password'])) {
                $stmt = $this->instance->prepare("UPDATE users SET password = '$hashedPassword' WHERE id = :idUser");
                $stmt->bindParam("idUser", $idUser, PDO::PARAM_STR);
                $stmt->execute();
                $_SESSION['message'] = "Le mot de passe a été modifié avec succès";
                header("location: ../user/monCompte");
            } else {
                $_SESSION['error'] = "Le mot de passe actuel saisi est incorrect";
                header("location: ../user/monCompte");
            }
        } else {
            $_SESSION['error'] = "Une erreur est survenue";
            header("location: ../user/monCompte");
        }
    }

    public function deleteAccount($idUser)
    {
        $stmt = $this->instance->prepare("DELETE FROM users WHERE id = :idUser");
        $stmt->bindParam("idUser", $idUser, PDO::PARAM_STR);
        $stmt->execute();

        // On détruit la session et on retourne à l'accueil
        unset($_SESSION['user']);
        session_destroy();

        header("location: ../../");

    }

    public function deleteAccountByAdmin($idUser)
    {
        $stmt = $this->instance->prepare("DELETE FROM users WHERE id = :idUser");
        $stmt->bindParam("idUser", $idUser, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION["message"] = "Profil supprimé avec succès.";
        header("location: administration");

    }

    public function createObjectFromFields($fields): User
    {
        $user = new User();



        $user->setId($fields['id'])
            ->setEmail($fields['email'])
            ->setPseudo($fields['pseudo'])
            ->setPassword($fields['password'])
            ->setRole($fields['role'])
            ->setIp($fields['ip'])
            ->setCreatedDate($fields['creation_date']);

        return $user;
    }

}