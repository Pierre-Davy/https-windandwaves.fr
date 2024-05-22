<?php

namespace App\Models\services;

use App\Models\dao\UserDao;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "PhpMailer/Exception.php";
require_once "PhpMailer/PHPMailer.php";
require_once "PhpMailer/SMTP.php";

class UserService
{

    private UserDao $userDao;


    public function __construct()
    {
        $this->userDao = new UserDao();
    }


    public function getAllUsers(): array
    {
        $users = $this->userDao->getUsers();
        return $users;
    }


    public function register($formData)
    {
        $patternRegexPseudo = "/^[a-zA-Z0-9_.-]*$/";
        $patternRegexMail = "/^[\w_.-]+@[\w-]+\.[a-z]{2,4}$/i";
        $patternRegexPassword = "/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/";

        /*
        Explication de la Regex du password
        ^ : Indique le début de la chaîne.
(?=.*?[A-Z]) : C'est une assertion positive qui vérifie la présence d'au moins une lettre majuscule.
(?=(.*[a-z]){1,}) : C'est une autre assertion positive qui vérifie la présence d'au moins une lettre minuscule.
(?=(.*[\d]){1,}) : Une autre assertion positive qui vérifie la présence d'au moins un chiffre.
(?=(.*[\W]){1,}) : Une autre assertion positive qui vérifie la présence d'au moins un caractère spécial (non-alphanumérique).
(?!.*\s) : C'est une assertion négative qui vérifie qu'il n'y a pas d'espaces dans le mot de passe.
.{8,} : Correspond à toute chaîne de caractères de longueur minimale 8.
        */


        // On vérifie que les champs du formulaires existent et sont correctement remplis
        if (isset($formData["pseudo"]) && !empty($formData["pseudo"]) && isset($formData["email"]) && !empty($formData["email"])
            && isset($formData["password"]) && !empty($formData["password"]) && isset($formData["passwordConfirm"]) && !empty($formData["passwordConfirm"])) {

            // les champs sont complets
            // on vérifie la concordance des mots de passes saisis
            if ($formData["password"] === $formData["passwordConfirm"]) {

                //les mots de passe sont identiques

                //on vérifie que les propriétés minimales du pseudo sont respectées
                if (strlen($formData["pseudo"]) > 0 && (strlen($formData["pseudo"]) < 3 || strlen($formData["pseudo"]) > 20)) {
                    $_SESSION['error'] = "Le pseudo ne respecte pas les consignes";
                    header("location: ../user/formRegister");
                    return;
                }
                if (!preg_match($patternRegexPseudo, $formData["pseudo"])) {
                    $_SESSION['error'] = "Le pseudo ne respecte pas les consignes";
                    header("location: ../user/formRegister");
                    return;
                }

                // on clean les données transmises par l'utilisateur
                //$pseudo = strip_tags($formData["pseudo"]);
                $pseudo = $formData["pseudo"];


                // On vérifie que le mail est valide
                if (!preg_match($patternRegexMail, $formData["email"])) {
                    $_SESSION['error'] = "L'email n'est pas valide";
                    header("location: ../user/formRegister");
                    return;
                }
                // on clean les données transmises par l'utilisateur
                $email = filter_var($formData["email"], FILTER_SANITIZE_EMAIL);

                // On vérifie que le mot de passe respecte les consignes de sécurité
                if (!preg_match($patternRegexPassword, $formData["password"])) {
                    $_SESSION['error'] = "Le mot de passe ne respecte pas les consignes de sécurité";
                    header("location: ../user/formRegister");
                    return;
                }
                // On hashe le mot de passe
                $password = password_hash($formData["password"], PASSWORD_ARGON2ID);

                // On récupère l'addresse IP de l'utilisateur
                $ip = $_SERVER['REMOTE_ADDR'];

                // On appelle la methode registerDao pour implémenter les données en bdd
                $this->userDao->register($pseudo, $email, $password, $ip);

            } else {
                $_SESSION['error'] = "Les mots de passe saisis ne sont pas identiques.";
                header("location: ../user/formRegister");
            }
        } else {
            $_SESSION['error'] = "Les champs de saisie doivent être correctement remplis.";
            header("location: ../user/formRegister");
        }
    }

    public function login($formData)
    {
        // On vérifie que les champs du formulaires existent et sont correctement remplis
        if (isset($formData["email"]) && !empty($formData["email"]) && isset($formData["password"]) && !empty($formData["password"])) {

            // les champs sont complets
            // on clean les données transmises par l'utilisateur
            $email = filter_var($formData["email"], FILTER_SANITIZE_EMAIL);
            $password = $formData["password"];


            // On appelle la methode registerDao pour implémenter les données en bdd
            $user = $this->userDao->login($email, $password);
            return $user;

        } else {
            $_SESSION['error'] = "Les champs de saisie doivent être correctement remplis.";
            header("location: ../user/formLogin");
        }
    }

    public function forgotPassword()
    {
        // On vérifie si le mail existe et n'est pas vide
        if (isset($_POST["email"]) && !empty($_POST["email"])) {

            $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

            // On vérifie si l'email existe en BDD


            if(!$this->userDao->emailExist($email)) {
                $_SESSION['error'] = "Email incorrect ou inconnu";
                header("location: ../user/formForgotPassword");
                return;
            }


            // On génére un password aléatoire
            $password = uniqid();
            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

            // On prépare le mail avec le message contenant le password temporaire et son entête
            $message = "Bonjour $email, voici votre nouveau mot de passe : $password <br>Vous pouvez revenir sur notre site en cliquant ici <a href=\"https://windandwaves.fr/\">Wind and Waves</a>";

            // On instancie phpMailer
            $mail = new PHPMailer(true);
            try {
                // Configuration du server de messagerie
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;

                // Config SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.fr'; // exemple 'smtp.gmail.com'
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@windandwaves.fr';
                $mail->Password = '@Afpa29200';

                // Charset
                $mail->CharSet = 'UTF-8';

                // Destinataires
                $mail->addAddress($email);
                $mail->addBCC("admin@windandwaves.fr"); // BCC = copie cachée


                // Expéditeur
                $mail->setFrom("admin@windandwaves.fr");

                // Contenu
                $mail->isHTML();

                $mail->Subject = "Wind and Waves : Votre nouveau mot de passe";
                $mail->Body = $message;

                // Envoi du mail
                $mail->send();

                // On update le password en base de données
                $this->userDao->resetPassword($email, $hashedPassword);
                $_SESSION['message'] = "Email envoyé avec succès.";
                header("location: ../../");

            } catch (Exception) {
                echo "Message non envoyé. erreur: {$mail->ErrorInfo}";
            }
        }
    }

    public function resetPassword()
    {
        // On vérifie que les données existes
        if (isset($_POST["actualPassword"]) && !empty($_POST["actualPassword"]) && isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["passwordConfirm"]) && !empty($_POST["passwordConfirm"])) {
            // On vérifie
            $idUser = strip_tags($_SESSION["user"]["id"]);
            $actualPassword = htmlspecialchars($_POST["actualPassword"]);
            // On verifie si les mots de passes saisis sont similaires
            if ($_POST["password"] !== $_POST["passwordConfirm"]) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header("location: ../user/resetPassword");
                return;
            }

            $hashedPassword = password_hash($_POST["password"], PASSWORD_ARGON2ID);

            // On vérifie la concordance du mots de passe actuel
            // Si ok update du mot de passe avec le nouveau mdp
            $this->userDao->resetPasswordWithForm($idUser, $actualPassword, $hashedPassword);

        }
    }

    public function deleteAccount()
    {
        if (isset($_POST["deleteConfirm"]) && !empty($_POST["deleteConfirm"])) {
            if ($_POST["deleteConfirm"] === "yes") {
                $idUser = strip_tags($_SESSION["user"]["id"]);
                $this->userDao->deleteAccount($idUser);
            } else {
                header("location: monCompte");
                return;
            }
        }
    }

    public function deleteAccountByAdmin()
    {
        if (isset($_POST["deleteConfirm"]) && !empty($_POST["deleteConfirm"])) {
            if ($_POST["deleteConfirm"] === "yes") {
                $idUser = strip_tags($_POST["idUser"]);
                $this->userDao->deleteAccountByAdmin($idUser);
            } else {
                $_SESSION["message"] = "Vous n'avez pas confirmé la suppression du profil";
                header("location: administration");
                return;
            }
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        return;
    }

}