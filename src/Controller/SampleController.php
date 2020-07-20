<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\Note;
use App\Entity\User;
use App\Repository\ChecklistRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class SampleController extends AbstractController
{
    public function index(CacheInterface $cache, ItemRepository $itemRepository, ChecklistRepository $checklistRepository, string $checklistId): Response
    {
        $checklist = $cache->get(
            'checklist-' . $checklistId,
            static function () use ($checklistRepository, $checklistId) {
                return $checklistRepository->find($checklistId);
            }
        );

        return $this->render(
            'sample.html.twig',
            [
                'user_first_name' => 'Chris',
                'checklist' => $checklist,
                'pusher_app_key' => 'aca6b37753a9e751466d', //getenv('PUSHER_KEY'),
                'pusher_auth_endpoint' => $this->generateUrl('pusher_authenticate'),
                'update_url' => $this->generateUrl('checklist_entry_update', ['checklistId' => $checklistId]),
                'note_url' => $this->generateUrl('checklist_add_note', ['checklistId' => $checklistId]),
            ]);
    }

    public function entry_update(CacheInterface $cache, EntityManagerInterface $entityManager, Security $security, ChecklistRepository $checklistRepository, ItemRepository $itemRepository, Request $request, string $checklistId): Response
    {

        $value = $request->request->get('value');
        $itemId = $request->request->get('itemId');

        $checklist = $checklistRepository->find($checklistId);
        assert($checklist !== null);
        $item = $itemRepository->find($itemId);
        assert($item !== null);
        assert($checklist->hasItem($item));

        $user = $security->getUser();
        assert($user instanceof User);

        $entry = (new Entry())->setValue($value)->setUser($user)->setChecklistItem($item);

        $entityManager->persist($entry);
        $entityManager->flush();

        $cache->delete('checklist-' . $checklistId);

        return $this->json(['status' => 'success']);
    }

    public function add_note(CacheInterface $cache, EntityManagerInterface $entityManager, Security $security, ChecklistRepository $checklistRepository, ItemRepository $itemRepository, Request $request, string $checklistId)
    {
        $noteText = $request->request->get('noteText');
        $itemId = $request->request->get('itemId');

        $checklist = $checklistRepository->find($checklistId);

        assert($checklist !== null);
        $item = $itemRepository->find($itemId);
        assert($item !== null);
        assert($checklist->hasItem($item));

        $user = $security->getUser();
        assert($user instanceof User);

        $note = (new Note())->setText($noteText)->setItem($item);

        $entityManager->persist($note);
        $entityManager->flush();

        $cache->delete('checklist-' . $checklistId);

        return $this->json(['status' => 'success']);
    }
}
