<?php
namespace App\Factories;

use DI\ContainerBuilder;

class ContainerFactory {

    public function __invoke()
    {
        $builder = new ContainerBuilder();
        if (PRODUCTION) {
            $builder->enableDefinitionCache();
            $builder->enableCompilation('tmp');
            $builder->writeProxiesToFile(true, 'tmp/proxies');
        }

        $builder->useAutowiring(true);

        $files = require 'config/__.php';
        foreach ($files as $file) {
            $builder->addDefinitions($file);
        }
        return $builder->build();
    }

}
