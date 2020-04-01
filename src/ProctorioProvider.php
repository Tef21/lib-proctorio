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

class ProctorioProvider
{
    /** @var ProctorioConfig $providerConfig */
    private $providerConfig;

    /** @var Encoder $encoder */
    private $encoder;

    /** @var Normalizer $normalizer */
    private $normalizer;

    /** @var RequestBuilder $requestBuilder */
    private $requestBuilder;

    /**
     * ProctorioProvider constructor.
     * @param Encoder $encoder
     * @param Normalizer $normalizer
     * @param RequestBuilder $requestBuilder
     */
    public function __construct(Encoder $encoder, Normalizer $normalizer, RequestBuilder $requestBuilder)
    {
        $this->encoder = $encoder;
        $this->normalizer = $normalizer;
        $this->requestBuilder = $requestBuilder;
    }


    /**
     * @param array $payload
     * @param string $secret
     * @return string
     */
    public function retrieve(array $payload, string $secret): string
    {
        $requestPayload = $payload;
        $requestPayload['oauth_signature'] = $this->createSignature($this->encoder, $this->normalizer, $requestPayload, $secret);
        $requestPayloadString = http_build_query($requestPayload);

        return $this->requestBuilder->buildRequest($requestPayloadString);
    }

    /**
     * @param Encoder $encoder
     * @param Normalizer $normalizer
     * @param array $payload
     * @param string $secret
     * @return string
     */
    private function createSignature(Encoder $encoder, Normalizer $normalizer, array $payload, string $secret): string
    {
        return (new SignatureBuilder())->buildSignature($encoder, $normalizer, $payload, $secret);
    }
}
