<?php
declare(strict_types=1);

namespace App\Controller\Registration;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function index(UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $plaintextPassword = $user->getPassword();

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        return $this->render('home/index.html.twig');
        // ...
    }
}