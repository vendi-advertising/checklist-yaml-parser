<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChecklistSessionController extends AbstractController
{
    /**
     * @Route("/start", name="checklist_session_start")
     */
    public function index()
    {
        return $this->render('checklist_session/index.html.twig', [
            'controller_name' => 'ChecklistSessionController',
        ]);
    }
}
