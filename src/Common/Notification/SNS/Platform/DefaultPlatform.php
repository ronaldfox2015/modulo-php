<?php

namespace Bumeran\Common\Notification\SNS\Platform;

use Bumeran\Common\Notification\SNS\Message\RawMessage;

/**
 * Class DefaultPlatform
 *
 * @package Bumeran\Common\Notification\SNS\Platform
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class DefaultPlatform extends AbstractPlatform
{
    public function __construct($tokenArm)
    {
        parent::__construct($tokenArm, PlatformType::UKNOW, new RawMessage());
    }
}
