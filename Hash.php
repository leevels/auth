<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2020 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Auth;

/**
 * Hash.
 */
class Hash implements IHash
{
    /**
     * 加密算法.
    */
    const ALGO = ':algo';

    /**
     * {@inheritDoc}
     */
    public function password(string $password, array $option = []): string
    {
        $algo = $option[self::ALGO] ?? PASSWORD_BCRYPT;

        return password_hash($password, (int) $algo, $option);
    }

    /**
     * {@inheritDoc}
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
