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

class ProctorioAccessProvider
{
    /** @var ProctorioRequestHandler */
    private $requestHandler;

    /** @var SignatureBuilder */
    private $signatureBuilder;

    /**
     * ProctorioProvider constructor.
     */
    public function __construct(ProctorioRequestHandler $requestHandler = null, SignatureBuilder $signatureBuilder = null)
    {
        $this->requestHandler = $requestHandler;
        $this->signatureBuilder = $signatureBuilder;

        if ($this->signatureBuilder === null) {
            $encoder = new Encoder();
            $normalizer = new Normalizer();
            $this->signatureBuilder = new SignatureBuilder($encoder, $normalizer);
        }

        if ($this->requestHandler === null) {
            $this->requestHandler = new ProctorioRequestHandler(
                sprintf(ProctorioConfig::LAUNCH_URL, ProctorioConfig::CURRENT_DEFAULT_REGION)
            );
        }
    }

    /**
     * @throws GuzzleException
     */
    public function retrieve(array $payload, string $secret): string
    {
        $payload['oauth_signature'] = $this->signatureBuilder->buildSignature($payload, $secret);
        $requestPayloadString = http_build_query($payload);

        $response = $this->requestHandler->execute($requestPayloadString);

        return (string) $response->getBody();
    }
}
