<?php

declare(strict_types=1);

/*
 * This file is part of the Runroom package.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Release;

class ComposerNormalizePostReleaseWorker extends ComposerNormalizePreReleaseWorker
{
    public function getPriority(): int
    {
        return 75;
    }
}