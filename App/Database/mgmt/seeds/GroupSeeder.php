<?php

require 'vendor/autoload.php';

use App\Factories\ContainerFactory;
use DI\DependencyException;
use DI\NotFoundException;
use Phinx\Seed\AbstractSeed;
use Psr\Container\ContainerInterface;

class GroupSeeder extends AbstractSeed
{
    /**
     * @var ContainerInterface $container
     * @var array $schools_data
     * @var array $groups_data
     */
    private $container;
    private $schools_data;
    private $groups_data;


    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->container = (new ContainerFactory)();

        $this->schools_data = [];
        $this->groups_data = [];

        foreach ($this->container->get("ics.data") as $school => $content)
        {
            $this->schools_data[] = [
                'name' => "$school",
                'url' => $content['url_base'],
                'fancy_name' => $content['fancy_name'],
            ];

            foreach ($content['classes'] as $class)
            {
                $this->groups_data[] = [
                    'name' => $class['name'],
                    'school' => $school,
                    'year' => $class['year'],
                    'url' => $class['url'] != '' ? $class['url'] : null,
                ];
            }
        }
    }

    public function run()
    {
        $schools = $this->table("schools");
        $groups = $this->table('groups');

        $schools->insert($this->schools_data)->saveData();
        $groups->insert($this->groups_data)->saveData();
    }
}
