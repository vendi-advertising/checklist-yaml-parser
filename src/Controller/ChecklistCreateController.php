<?php

namespace App\Controller;

use App\Entity\Checklist;
use App\Exceptions\InvalidItemTypeException;
use App\Repository\TemplateRepository;
use App\Service\ChecklistParser;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChecklistCreateController extends AbstractController
{
    public function new(TemplateRepository $checklistTemplateRepository): Response
    {
        $templates = $checklistTemplateRepository->findAll();
        return $this->render(
            'checklist_create/index.html.twig',
            [
                'templates' => $templates,
            ]
        );
    }

    /**
     * @param Request            $request
     * @param TemplateRepository $templateRepository
     * @param ChecklistParser    $checklistParser
     * @param string             $templateId
     *
     * @return Response
     * @throws InvalidItemTypeException
     */
    public function new_with_template(Request $request, TemplateRepository $templateRepository, ChecklistParser $checklistParser, string $templateId): Response
    {
        $template = $templateRepository->find($templateId);
        assert(null !== $template);
        $baseChecklist = $checklistParser->parseTemplate($template);

        $tokenHtmlAttribute = 'csrf-token';
        $tokenId = 'create-new-checklist';

        if ($request->isMethod(Request::METHOD_POST)) {
            $submittedToken = $request->request->get($tokenHtmlAttribute);
            if (!$this->isCsrfTokenValid($tokenId, $submittedToken)) {
                throw new RuntimeException('Invalid CSRF token');
            }

            $realChecklist = new Checklist();
            $realChecklist->setDescription($request->request->get('checklistName'));

            foreach ($request->request->keys() as $key) {
                if (0 !== strpos($key, 'section-')) {
                    continue;
                }
            }

        }
        return $this->render(
            'checklist_create/edit.html.twig',
            [
                'checklist' => $baseChecklist,
                'tokenId' => $tokenId,
                'tokenHtmlAttribute' => $tokenHtmlAttribute,
            ]
        );
    }
}
