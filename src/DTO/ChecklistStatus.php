<?php

namespace App\DTO;

final class ChecklistStatus
{
    private int $completedItems = 0;
    private int $notApplicableItems = 0;
    private int $incompleteItems = 0;

    public function getIncompleteItems(): int
    {
        return $this->incompleteItems;
    }

    public function getNotApplicableItems(): int
    {
        return $this->notApplicableItems;
    }

    public function getCompletedItems(): int
    {
        return $this->completedItems;
    }

    public function incrementCompletedItems(): void
    {
        $this->completedItems++;
    }

    public function incrementNotApplicableItems(): void
    {
        $this->notApplicableItems++;
    }

    public function incrementIncompleteItems(): void
    {
        $this->incompleteItems++;
    }

    public function getTotalItemCount(): int
    {
        return $this->completedItems + $this->incompleteItems + $this->notApplicableItems;
    }

    public function getApplicableItemCount(): int
    {
        return $this->completedItems + $this->incompleteItems;
    }

    public function getPercentDone(): float
    {
        return ($this->completedItems / $this->getApplicableItemCount()) * 100;
    }
}