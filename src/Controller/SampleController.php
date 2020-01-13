<?php

namespace App\Controller;

use App\Exceptions\ArrayMissingRequiredKeyException;
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
     * @Route("/sample", name="sample")
     *
     * @return Response
     * @throws ArrayMissingRequiredKeyException
     * @throws InvalidItemTypeException
     */
    public function index()
    {
        $checklist = $this->checklistParser->parseFileFromRoot('sample.yaml');

        return $this->render(
            'sample.html.twig',
            [
                'user_first_name' => 'Chris',
                'checklist' => $checklist,
            ]);
    }
}
