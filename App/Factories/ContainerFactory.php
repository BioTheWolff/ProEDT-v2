<?php
namespace App\Factories;

use DI\ContainerBuilder;

/**
 * Creates the container by building it with the production const and the different config files
 *
 * @package App\Factories
 * @author Vasco Compain
 */
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
