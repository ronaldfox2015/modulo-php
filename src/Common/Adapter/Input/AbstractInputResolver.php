<?php
declare(strict_types = 1);

namespace Bumeran\Common\Adapter\Input;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InputResolver
 *
 * @package Bumeran\Common\Adapter\Input
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
abstract class AbstractInputResolver extends OptionsResolver
{
    private static $resolversByClass = [];
    private $options = [];

    public function __construct(array $options = [])
    {
        $class = get_class($this);

        if (! isset(self::$resolversByClass[$class])) {
            self::$resolversByClass[$class] = new OptionsResolver();
            $this->configureOptions(self::$resolversByClass[$class]);
        }

        $this->options = self::$resolversByClass[$class]->resolve($options);
    }

    /**
     * Abstract resolver option
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    abstract protected function configureOptions(OptionsResolver $resolver);

    /**
     * Magic call to get a value option
     *
     * @param String $name
     * @param array|null $arguments
     * @return mixed
     */
    public function __call(String $name, array $arguments = null)
    {
         return $this->options[$name];
    }
}
