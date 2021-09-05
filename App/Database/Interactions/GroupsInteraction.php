<?php

namespace App\Database\Interactions;

use App\Database\Managers\GroupsManager;

class GroupsInteraction
{
    /**
     * @var GroupsManager $manager
     */
    private $manager;

    public function __construct(GroupsManager $manager)
    {
        $this->manager = $manager;
    }

    public function get_school_groups(): array
    {
        $entries = $this->manager->select_groups_order_by_school();
        $result = [];

        foreach ($entries as $entry)
        {
            if (!in_array($entry->school, array_keys($result)))
                $result[$entry->school] = ['fancy_name' => $entry->fancy_name, 'classes' => []];

            $result[$entry->school]['classes'][] = $entry->name;
        }

        return $result;
    }
}