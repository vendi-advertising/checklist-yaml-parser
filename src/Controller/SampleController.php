<?php

/** @noinspection DuplicatedCode */

namespace App\Controller;

use App\Entity\User;
use App\Repository\ChecklistRepository;
use App\Service\ChecklistFormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class SampleController extends AbstractController
{
    public function index(CacheInterface $cache, ChecklistRepository $checklistRepository, Security $security, string $checklistId): Response
    {

        if (!$checklistRepository->find($checklistId)) {
            throw new \RuntimeException('The selected checklist does not exist');
        }

        $checklist = $cache->get(
            'checklist-' . $checklistId,
            static function () use ($checklistRepository, $checklistId) {
                return $checklistRepository->find($checklistId);
            }
        );

        $user = $security->getUser();
        assert($user instanceof User);

        return $this->render(
            'sample.html.twig',
            [
                'new_note_event' => ChecklistFormHelper::PUSHER_EVENT_NOTE_NOTE,
                'user_first_name' => 'Chris',
                'user_id' => $user->getId(),
                'checklist' => $checklist,
                'pusher_app_key' => $this->getParameter('pusherKey'),
                'pusher_cluster' => $this->getParameter('pusherCluster'),
                'pusher_auth_endpoint' => $this->generateUrl('pusher_authenticate'),
                'update_url' => $this->generateUrl('checklist_entry_update', ['checklistId' => $checklistId]),
                'note_url' => $this->generateUrl('checklist_add_note', ['checklistId' => $checklistId]),
            ]);
    }

    public function entry_update(Request $request, ChecklistFormHelper $checklistFormHelper, string $checklistId): Response
    {

        $value = $request->request->get('value');
        $itemId = $request->request->get('itemId');

        $checklistFormHelper->addNewEntry($value, $itemId, $checklistId);

        return $this->json(['status' => 'success']);
    }

    public function add_note(Request $request, ChecklistFormHelper $checklistFormHelper, string $checklistId)
    {
        $noteText = $request->request->get('noteText');
        $itemId = $request->request->get('itemId');

        $checklistFormHelper->addNewNote($noteText, $itemId, $checklistId);

        return $this->json(['status' => 'success']);
    }
}
