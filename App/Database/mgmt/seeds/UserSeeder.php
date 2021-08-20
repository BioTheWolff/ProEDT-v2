<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    const USERS_DATA = [
        [
            'username' => 'foo',
            'password' => '$2y$10$6zvGVP2RSrCaPc8HYCkybemvVz2LQzVIL69NJszx3WyOQiv0wuGGC' // test
        ], [
            'username' => 'test',
            'password' => '$2y$10$YSNPAz0o/7lb9pfMJ3lS0.X7Gg2u77Y7aHZ6KQKqcEO0FwCFrV6nW' // mytest2
        ]
    ];

    public function run()
    {
        $users = $this->table('users');
        $users->insert(UserSeeder::USERS_DATA)
            ->saveData();
    }
}
