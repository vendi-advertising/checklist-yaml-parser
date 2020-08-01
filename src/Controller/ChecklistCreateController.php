<?php

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\Item;
use App\Entity\Section;
use App\Entity\User;
use App\Exceptions\InvalidItemTypeException;
use App\Hashing\ObjectHashingException;
use App\Repository\ChecklistRepository;
use App\Repository\TemplateRepository;
use App\Service\ChecklistParser;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ChecklistCreateController extends AbstractController
{
    public function done(ChecklistRepository $checklistRepository, string $checklistId): Response
    {
        $checklist = $checklistRepository->find($checklistId);
        assert(null !== $checklist);

        return $this->render(
            'checklist_create/done.html.twig',
            [
                'checklist' => $checklist,
            ]
        );
    }

    private function fixSort(string $sort = null): string
    {
        switch ($sort) {
            case 'c.dateTimeCreated':
            case 'c.description':
            case 't.dateTimeCreated':
            case 't.name':
            case 'u.email':
                break;

            default:
                $sort = 'c.dateTimeCreated';
        }

        return $sort;
    }

    private function fixDirection(string $direction = null): string
    {
        return in_array($direction, ['ASC', 'DESC']) ? $direction : 'DESC';
    }

    public function list(ChecklistRepository $checklistRepository, Request $request): Response
    {
        $fixedSort = $this->fixSort($request->query->get('sort'));
        $fixedDirection = $this->fixDirection($request->query->get('direction'));
        $sortTable = explode('.', $fixedSort)[0];
        $checklists = $checklistRepository->findForListing($fixedSort, $fixedDirection);
        return $this->render(
            'checklist.html.twig',
            [
                'checklists' => $checklists,
                'sort' => $fixedSort,
                'direction' => $fixedDirection,
                'groupBy' => $sortTable,
            ]
        );
    }

    public function new(TemplateRepository $templateRepository): Response
    {
        $templates = $templateRepository->findAll();
        return $this->render(
            'checklist_create/index.html.twig',
            [
                'templates' => $templates,
            ]
        );
    }

    /**
     * @param Security               $security
     * @param EntityManagerInterface $entityManager
     * @param Request                $request
     * @param TemplateRepository     $templateRepository
     * @param ChecklistParser        $checklistParser
     * @param string                 $templateId
     *
     * @return Response
     * @throws InvalidItemTypeException
     * @throws JsonException
     * @throws ObjectHashingException
     */
    public function new_with_template(Security $security, EntityManagerInterface $entityManager, Request $request, TemplateRepository $templateRepository, ChecklistParser $checklistParser, string $templateId): Response
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

            $sectionIds = [];

            foreach ($request->request->keys() as $key) {
                if (0 !== strpos($key, 'section-')) {
                    continue;
                }

                $parts = explode('-', $key);
                assert(2 === count($parts));

                array_shift($parts);
                $sectionIds[array_shift($parts)] = [];
            }

            foreach ($request->request->keys() as $key) {
                if (0 !== strpos($key, 'item-')) {
                    continue;
                }

                $parts = explode('-', $key);
                assert(4 === count($parts));

                array_shift($parts);
                $itemId = array_shift($parts);
                array_shift($parts);
                $sectionId = array_shift($parts);

                assert(array_key_exists($sectionId, $sectionIds));
                $sectionIds[$sectionId][] = $itemId;
            }

            $currentUser = $security->getUser();
            assert($currentUser instanceof User);

            $realChecklist = new Checklist();
            $realChecklist->setTemplate($template);
            $realChecklist->setCreatedBy($currentUser);
            $realChecklist->setDescription($request->request->get('checklistName'));

            foreach ($sectionIds as $sectionId => $itemIds) {
                foreach ($baseChecklist->getSections() as $baseSection) {
                    if ($sectionId === $baseSection->getHash()) {
                        $realSection = (new Section())->setName($baseSection->getName());

                        foreach ($itemIds as $itemId) {
                            foreach ($baseSection->getItems() as $baseItem) {
                                if ($itemId === $baseItem->getHash()) {
                                    $realSection->addItem(
                                        (new Item())->setName($baseItem->getName())
                                    );
                                }
                            }
                        }

                        $realChecklist
                            ->addSection(
                                $realSection
                            );
                        continue;
                    }
                }
            }

            $entityManager->persist($realChecklist);
            $entityManager->flush();

            return $this->redirectToRoute('checklist_create_done', ['checklistId' => $realChecklist->getId()]);

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
