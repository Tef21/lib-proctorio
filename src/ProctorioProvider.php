<?php

namespace oat\Proctorio;

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
class ProctorioProvider
{

    public const TESTING_KEY = 'bb795227c87748d09c4357f362a29ebf';

    /**
     * time()
     * @var int
     */
    private $time;

    private $providerConfig;

    /**
     * ProctorioProvider constructor.
     * @param ProctorioConfig $providerConfig
     */
    public function __construct(ProctorioConfig $providerConfig)
    {
        $this->providerConfig = $providerConfig;
    }

    public function retrieve()
    {
        $config = $this->getProctorioConfig();
        $encoder = $this->getEncoder();
        $normalizer = $this->getNormilizer();
        $requestBuilder = $this->getRequestBuilder();

        $payload = $this->buildPayload();

        $requestPayload = $this->providerConfig();
            //$config->configure($payload);

        $signature = $this->createSignature($encoder, $normalizer, $requestPayload);

        $requestPayload['oauth_signature'] = $signature;

        $requestPayloadString = $normalizer->normalize($requestPayload);


        return $requestBuilder->buildRequest($requestPayloadString);
    }

    private function buildPayload(): array
    {
        $this->time = time();

        return
            [
                ProctorioConfig::LAUNCH_URL => ProctorioConfig::PROCTORIO_URL,
                ProctorioConfig::USER_ID => 'mike123456',
                ProctorioConfig::OAUTH_CONSUMER_KEY => self::TESTING_KEY,
                ProctorioConfig::EXAM_START => 'https://proctorio.com/customers',
                ProctorioConfig::EXAM_TAKE => 'https://proctorio.com/about',
                ProctorioConfig::EXAM_END => 'https://proctorio.com/platform',
                ProctorioConfig::EXAM_SETTINGS => 'webtraffic',
                ProctorioConfig::FULL_NAME => 'name withSpace',//there might be an issue with spaces inside the string
                ProctorioConfig::EXAM_TAG => 'tag',
                ProctorioConfig::OAUTH_TIMESTAMP => $this->time,
                ProctorioConfig::OAUTH_NONCE => 'mike123456nounce123',
            ];
    }

    private function getRequestBuilder(): RequestBuilder
    {
        return new RequestBuilder();
    }

    private function getProctorioConfig(): ProctorioConfig
    {
        return new ProctorioConfig();
    }

    private function getEncoder(): Encoder
    {
        return new Encoder();
    }

    private function getNormilizer(): Normalizer
    {
        return new Normalizer();
    }

    /**
     * @param Encoder $encoder
     * @param Normalizer $normalizer
     * @param array $payload
     * @return string
     * @return string
     */
    private function createSignature(Encoder $encoder, Normalizer $normalizer, array $payload): string
    {
        return (new SignatureBuilder())->buildSignature($encoder, $normalizer, $payload);
    }
}
