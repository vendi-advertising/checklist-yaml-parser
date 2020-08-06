<?php

/** @noinspection DuplicatedCode */

namespace App\Controller;

use App\Entity\User;
use App\Repository\ChecklistRepository;
use App\Service\ChecklistFormHelper;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JsonException;
use Psr\Cache\InvalidArgumentException;
use Pusher\PusherException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class SampleController extends AbstractController
{
    /**
     * @param CacheInterface      $cache
     * @param ChecklistRepository $checklistRepository
     * @param Security            $security
     * @param string              $checklistId
     *
     * @return Response
     * @throws InvalidArgumentException
     */
    public function view_checklist(CacheInterface $cache, ChecklistRepository $checklistRepository, Security $security, string $checklistId): Response
    {

        if (!$checklistRepository->find($checklistId)) {
            throw new RuntimeException('The selected checklist does not exist');
        }

        $checklist = $cache->get(
            'checklist-' . $checklistId,
            static function () use ($checklistRepository, $checklistId) {
                return $checklistRepository->findOneById($checklistId);
            }
        );

        $user = $security->getUser();
        assert($user instanceof User);

        return $this->render(
            'sample.html.twig',
            [
                'new_note_event' => ChecklistFormHelper::PUSHER_EVENT_NOTE_NOTE,
                'status_change_event' => ChecklistFormHelper::PUSHER_EVENT_STATUS_CHANGE,
                'user' => $user,
                'checklist' => $checklist,
            ]);
    }

    /**
     * @param Request             $request
     * @param ChecklistFormHelper $checklistFormHelper
     * @param string              $checklistId
     *
     * @return Response
     */
    public function entry_update(Request $request, ChecklistFormHelper $checklistFormHelper, string $checklistId): Response
    {

        $value = $request->request->get('value');
        $itemId = $request->request->get('itemId');

        $checklistFormHelper->addNewEntry($value, $itemId, $checklistId);

        return $this->json(['status' => 'success']);
    }

    /**
     * @param Request             $request
     * @param ChecklistFormHelper $checklistFormHelper
     * @param string              $checklistId
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws JsonException
     * @throws PusherException
     */
    public function add_note(Request $request, ChecklistFormHelper $checklistFormHelper, string $checklistId): Response
    {
        $noteText = $request->request->get('noteText');
        $itemId = $request->request->get('itemId');

        $checklistFormHelper->addNewNote($noteText, $itemId, $checklistId);

        return $this->json(['status' => 'success']);
    }
}
