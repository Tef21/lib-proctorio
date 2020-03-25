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

namespace oat\Proctorio;

class ProctorioService
{
    /** @var ProctorioProvider $provider */
    private $provider;

    /** @var ProctorioConfig $config */
    private $config;

    public function __construct()
    {
        $this->config = new ProctorioConfig();
        $encoder = new Encoder;
        $normalizer = new Normalizer();
        $requestBuilder = new RequestBuilder();

        $this->provider = new ProctorioProvider($encoder, $normalizer, $requestBuilder);
    }


    public function callRemoteProctoring(array $config): string
    {
        return $this->provider->retrieve($config);
    }

    public function buildConfig(array $parameters): array
    {
        return $this->config->configure($parameters);
    }
}
