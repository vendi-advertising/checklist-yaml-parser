<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersController extends AbstractController
{
    public function viewUsers(UserRepository $userRepository): Response
    {
        return $this->render(
            'admin/users/view.html.twig',
            [
                'users' => $userRepository->findAll(),
            ]
        );
    }
}