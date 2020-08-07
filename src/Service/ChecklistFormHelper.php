<?php

namespace App\Service;

use App\Entity\Entry;
use App\Entity\Note;
use App\Entity\User;
use App\Repository\ChecklistRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JsonException;
use Psr\Cache\InvalidArgumentException;
use Pusher\Pusher;
use Pusher\PusherException;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

class ChecklistFormHelper
{
    // TODO: Rename typo
    public const PUSHER_EVENT_NOTE_NOTE = 'checklist-new-note';
    public const PUSHER_EVENT_STATUS_CHANGE = 'checklist-status-change';

    private CacheInterface $cache;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private ChecklistRepository $checklistRepository;
    private ItemRepository $itemRepository;
    private Pusher $pusher;

    public function __construct(CacheInterface $cache, EntityManagerInterface $entityManager, Security $security,
                                ChecklistRepository $checklistRepository, ItemRepository $itemRepository, Pusher $pusher)
    {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->checklistRepository = $checklistRepository;
        $this->itemRepository = $itemRepository;
        $this->pusher = $pusher;
    }

    /**
     * @param string $noteText
     * @param string $itemId
     * @param string $checklistId
     *
     * @throws InvalidArgumentException
     * @throws PusherException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws JsonException
     */
    public function addNewNote(string $noteText, string $itemId, string $checklistId): void
    {
        $checklist = $this->checklistRepository->findOneByIdOrThrow($checklistId);
        $item = $this->itemRepository->findOneByIdOrThrow($itemId);
        $section = $item->getSection();
        assert(null !== $section);
        assert($checklist->hasItem($item));

        $user = $this->security->getUser();
        assert($user instanceof User);

        $note = (new Note())->setText($noteText)->setItem($item)->setUser($user);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        $this->cache->delete('checklist-' . $checklistId);

        $noteCount = $this->itemRepository->countNotes($item->getId());

        $this->pusher
            ->trigger(
                'private-' . $checklist->getId(),
                self::PUSHER_EVENT_NOTE_NOTE,
                json_encode(
                    [
                        'checklist' => $checklist->getId(),
                        //TODO: Is section needed?
                        'section' => $section->getId(),
                        'item' => $item->getId(),
                        'noteText' => $noteText,
                        'noteId' => $note->getId(),
                        'newNoteCountForItem' => $noteCount,
                        'user' => $user,
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
    }

    /**
     * @param string $value
     * @param string $itemId
     * @param string $checklistId
     *
     * @param string $instanceId
     *
     * @return Entry
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws PusherException
     */
    public function addNewEntry(string $value, string $itemId, string $checklistId, string $instanceId): Entry
    {
        $checklist = $this->checklistRepository->findOneByIdOrThrow($checklistId);
        $item = $this->itemRepository->findOneByIdOrThrow($itemId);
        $section = $item->getSection();
        assert(null !== $section);
        assert($checklist->hasItem($item));

        $user = $this->security->getUser();
        assert($user instanceof User);

        $entry = (new Entry())->setValue($value)->setUser($user)->setChecklistItem($item);

        $this->entityManager->persist($entry);
        $this->entityManager->flush();

        $this->cache->delete('checklist-' . $checklistId);

        $this
            ->pusher
            ->trigger(
                'private-' . $checklist->getId(),
                self::PUSHER_EVENT_STATUS_CHANGE,
                json_encode(
                    [
                        'checklist' => $checklist->getId(),
                        //TODO: Is section needed?
                        'section' => $section->getId(),
                        'item' => $item->getId(),
                        'entryValue' => $value,
                        'entryId' => $entry->getId(),
                        'user' => $user,
                        'instanceId' => $instanceId,
                    ],
                    JSON_THROW_ON_ERROR
                )
            );

        return $entry;
    }
}
