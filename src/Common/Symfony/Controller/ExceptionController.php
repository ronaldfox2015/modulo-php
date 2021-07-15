<?php

namespace Bumeran\Common\Symfony\Controller;

use Bumeran\Common\Symfony\Response\JsonResponse;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;

/**
 * Class ExceptionController
 *
 * @package Bumeran\Common\Symfony\Controller
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class ExceptionController extends BaseExceptionController
{
    public function showAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null,
        $format = 'json'
    ) {


        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $showException = $request->attributes->get('showException', $this->debug);

        $code = $exception->getStatusCode();
        $message = $exception->getMessage();

        $data = [
            'code'    => $code,
            'message' => $message
        ];

        if ($showException && $this->debug) {
            $data['content'] = $currentContent;
        }

        return new JsonResponse(array_filter($data), $code);
    }
}
