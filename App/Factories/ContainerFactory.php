<?php
namespace App\Factories;

use DI\Container;
use DI\ContainerBuilder;
use Exception;

/**
 * Creates the container by building it with the production const and the different config files
 *
 * @package App\Factories
 * @author Vasco Compain
 */
class ContainerFactory {

    /**
     * @throws Exception
     */
    public function __invoke(): Container
    {
        $builder = new ContainerBuilder();
        if (PRODUCTION && ACCEPTS_CACHING) {
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
