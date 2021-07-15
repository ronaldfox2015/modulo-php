<?php

namespace Bumeran\Common\Notification\SNS\Platform;

use Bumeran\Common\AbstractEnum;

/**
 * Class PlatformType
 *
 * @package Bumeran\Common\Notification\SNS\Platform
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class PlatformType extends AbstractEnum
{
    const ANDROID = 'android';
    const IOS = 'ios';
    const WP = 'windows';
    const UKNOW = 'default';
}
