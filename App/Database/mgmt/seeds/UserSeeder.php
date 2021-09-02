<?php

require 'vendor/autoload.php';

use App\Factories\ContainerFactory;
use DI\DependencyException;
use DI\NotFoundException;
use Phinx\Seed\AbstractSeed;
use Psr\Container\ContainerInterface;

class UserSeeder extends AbstractSeed
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    private $data;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->container = (new ContainerFactory)();

        $this->data = [];
        foreach ($this->container->get("api.users") as $username => $password)
        {
            $this->data[] = ['username' => $username, 'password' => $password];
        }
    }

    public function run()
    {
        $users = $this->table('users');
        $users->insert($this->data)
            ->saveData();
    }
}
