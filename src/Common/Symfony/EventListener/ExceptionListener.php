<?php

namespace Bumeran\Common\Symfony\EventListener;

use Bumeran\Common\Exception\ServerException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface as OptionResolverException;

/**
 * Class ExceptionListener
 *
 * @package Bumeran\Common\Symfony\EventListener
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class ExceptionListener implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof OptionResolverException) {
            $event->setException(new BadRequestHttpException($exception->getMessage(), $exception));
            return;
        }

        /**
         * En el caso que se envie una de estas excepciones, mostrar un mensaje generico
         * del error con status 500(Server error).
         */
        foreach ($this->getNotAllowedExceptons() as $instance) {
            if ($exception instanceof $instance) {
                $event->setException(
                    new ServerException('Ocurrió un error interno. Intentelo nuevamente', 500, $exception)
                );
                return;
            }
        }
    }

    private function getNotAllowedExceptons()
    {
        return [
            // Doctrine Type
            \Doctrine\ORM\ORMException::class,
            \Doctrine\DBAL\DBALException::class,
            \Doctrine\DBAL\Driver\PDOException::class,

            // Aws Type
            \Aws\Exception\AwsException::class,

            // Solarium Type
            \Solarium\Exception\ExceptionInterface::class,

            // Commons Type
            \PDOException::class
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 5],
        ];
    }
}
