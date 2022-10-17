<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\ChoicesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotContaoChoicesBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }
}
