<?php


namespace Bumeran\Common\Adapter\Persistence\Doctrine;

use Bumeran\Common\Exception\Exception;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

/**
 * Class DoctrineProvider
 *
 * @package Bumeran\Common\Adapter\Persistence\Doctrine
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class DoctrineProvider
{

    /**
     * Return a entity manager instance
     *
     * @param array $config
     * @param string $env
     * @return EntityManager
     * @throws Exception
     */
    public static function getEntityManager(array $config, $env = 'default')
    {
        $configuration = new Configuration();

        $namespaces = $config['orm']['namespaces'];

        $configuration->setAutoGenerateProxyClasses(true);
        $configuration->setProxyDir($config['orm']['proxy']['dir']);
        $configuration->setProxyNamespace($config['orm']['proxy']['namespace']);
        $configuration->setNamingStrategy(new UnderscoreNamingStrategy());
        $configuration->setQueryCacheImpl(new ArrayCache());
        $configuration->setMetadataCacheImpl(new ArrayCache());
        $configuration->setResultCacheImpl(new ArrayCache());
        $configuration->setMetadataDriverImpl(new SimplifiedYamlDriver($namespaces));
        $configuration->setFilterSchemaAssetsExpression('~^(?!db)~');

        foreach ($namespaces as $alias => $namespace) {
            $configuration->addEntityNamespace($alias, $namespace);
        }

        if (false === isset($config['dbal']['connections'][$env])) {
            throw new Exception(sprintf('Database environment %s not exists', $env));
        }

        $em = EntityManager::create($config['dbal']['connections'][$env], $configuration);

        $types = array_replace(isset($config['orm']['types']) ? $config['orm']['types'] : [], [
            'enum' => 'string',
            'set' => 'string',
            'bit' => 'boolean'
        ]);

        foreach ($types as $class => $type) {
            $em->getConnection()
               ->getDatabasePlatform()
               ->registerDoctrineTypeMapping($class, $type);
        }

        return $em;
    }

    /**
     * @return array
     */
    public static function getCommands()
    {
        return [
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
        ];
    }
}
