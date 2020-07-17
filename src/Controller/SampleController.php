<?php

namespace App\Controller;

use App\Repository\ChecklistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SampleController extends AbstractController
{
    public function index(ChecklistRepository $checklistRepository, string $checklistId): Response
    {
        $checklist = $checklistRepository->find($checklistId);
        return $this->render(
            'sample.html.twig',
            [
                'user_first_name' => 'Chris',
                'checklist' => $checklist,
                'pusher_app_key' => 'aca6b37753a9e751466d', //getenv('PUSHER_KEY'),
                'pusher_auth_endpoint' => $this->generateUrl('pusher_authenticate'),
            ]);
    }
}
