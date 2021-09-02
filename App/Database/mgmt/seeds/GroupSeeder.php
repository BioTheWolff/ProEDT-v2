<?php

require 'vendor/autoload.php';

use App\Factories\ContainerFactory;
use DI\DependencyException;
use DI\NotFoundException;
use Phinx\Seed\AbstractSeed;
use Psr\Container\ContainerInterface;

class GroupSeeder extends AbstractSeed
{
    const GROUPS_DATA = [
        ['name' => "a1", 'major' => 'iut'],
        ['name' => "a2", 'major' => 'iut'],
    ];

    /**
     * @var ContainerInterface $container
     */
    private $container;

    private $SUBGROUPS_DATA;


    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->container = (new ContainerFactory)();

        $subgroups = ['s1', 's2', 's3', 's4', 's5', 's6', 'g1', 'g2', 'g3', 'g4', 'g5', 'q1', 'q2', 'q3', 'q4', 'q5'];

        $this->SUBGROUPS_DATA = [];

        foreach ($subgroups as $e)
        {
            $url = $this->container->get("ics.url.data.iut.$e");
            $this->SUBGROUPS_DATA[] = [
                'name' => $e,
                'parent' => str_starts_with($e, "s") ? "a1" : "a2",
                'url' => $url != '' ? $url : null
            ];
        }
    }

    public function run()
    {
        $users = $this->table('groups');

        $users->insert(GroupSeeder::GROUPS_DATA)->saveData();
        $users->insert($this->SUBGROUPS_DATA)->saveData();
    }
}
