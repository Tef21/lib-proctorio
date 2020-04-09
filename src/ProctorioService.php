<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */

declare(strict_types=1);

namespace oat\Proctorio;

use GuzzleHttp\Exception\GuzzleException;
use oat\Proctorio\Exception\ProctorioParameterException;

class ProctorioService implements RemoteProctoringInterface
{
    /** @var ProctorioAccessProvider $provider */
    private $provider;

    public function __construct(ProctorioAccessProvider $proctorioProvider = null)
    {
        $this->provider = $proctorioProvider;

        if ($this->provider === null) {
            $this->provider = new ProctorioAccessProvider();
        }
    }

    /**
     * @param array $parameters paramteres required by ProctorioConfig
     * @param string $key oauthkey
     * @param string $secret oauth secret
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \oat\Proctorio\Exception\ProctorioParameterException
     */
    public function callRemoteProctoring(array $parameters, string $key, string $secret): string
    {
        $config = new ProctorioConfig();
        return $this->provider->retrieve($config->configure($parameters, $key), $secret);
    }
}
