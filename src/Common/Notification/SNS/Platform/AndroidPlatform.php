<?php

namespace Bumeran\Common\Notification\SNS\Platform;

use Bumeran\Common\Notification\SNS\Message\AndroidMessage;

/**
 * Class AndroidPlatform
 *
 * @package Bumeran\Common\Notification\SNS\Platform
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class AndroidPlatform extends AbstractPlatform
{
    public function __construct($tokenArm)
    {
        parent::__construct($tokenArm, PlatformType::ANDROID, new AndroidMessage());
    }
}
