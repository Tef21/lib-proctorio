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

use oat\Proctorio\Exception\InvalidProctorioResponseException;
use oat\Proctorio\Response\ProctorioResponse;

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
        $endpoint = sprintf(ProctorioConfig::PROCTORIO_URL, ProctorioConfig::CURRENT_DEFAULT_REGION);
        $this->requestHandler = $requestHandler ?? new ProctorioRequestHandler($endpoint);
        $this->signatureBuilder = $signatureBuilder ?? new SignatureBuilder($endpoint);
    }

    /**
     * @throws InvalidProctorioResponseException
     */
    public function retrieve(array $payload, string $secret): ProctorioResponse
    {
        $payload['oauth_signature'] = $this->signatureBuilder->buildSignature($payload, $secret);
        $requestPayloadString = http_build_query($payload);

        return $this->requestHandler->execute($requestPayloadString);
    }
}
