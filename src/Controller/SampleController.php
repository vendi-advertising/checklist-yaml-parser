<?php

namespace App\Controller;

use App\Exceptions\InvalidItemTypeException;
use App\Service\ChecklistParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SampleController extends AbstractController
{
    private ChecklistParser $checklistParser;

    public function __construct(ChecklistParser $checklistParser)
    {
        $this->checklistParser = $checklistParser;
    }

    /**
     * @Route("/website-launch", name="website-launch")
     *
     * @return Response
     * @throws InvalidItemTypeException
     */
    public function website_launch(): Response
    {
        $checklist = $this->checklistParser->parseFileFromRoot('./config/checklists/website-launch.yaml');

        return $this->render(
            'sample.html.twig',
            [
                'user_first_name' => 'Chris',
                'checklist' => $checklist,
            ]);
    }

    /**
     * @Route("/website-onboarding", name="website-onboarding")
     *
     * @return Response
     * @throws InvalidItemTypeException
     */
    public function website_onboarding(): Response
    {
        $checklist = $this->checklistParser->parseFileFromRoot('./config/checklists/website-onboarding.yaml');

        return $this->render(
            'sample.html.twig',
            [
                'user_first_name' => 'Chris',
                'checklist' => $checklist,
            ]);
    }
}
