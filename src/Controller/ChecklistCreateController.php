<?php

namespace App\Controller;

use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChecklistCreateController extends AbstractController
{
    /**
     * @Route("/checklist/create", name="checklist_create")
     */
    public function index(TemplateRepository $checklistTemplateRepository)
    {
        $templates = $checklistTemplateRepository->findAll();
        return $this->render(
            'checklist_create/index.html.twig',
            [
                'templates' => $templates,
            ]
        );
    }
}
