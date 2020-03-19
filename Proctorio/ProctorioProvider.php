<?php

namespace Proctorio;

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

    public function retrieve()
    {
        $config = $this->getProctorioConfig();
        $encoder = $this->getEncoder();
        $normalizer = $this->getNormilizer();
        $requestBuilder = $this->getRequestBuilder();

        $payload = $this->buildPayload();

        $requestPayload = $config->configure($payload);

        //signature
        $signature = $this->createSignature($encoder, $normalizer, $requestPayload);

        echo $signature . PHP_EOL;

        $requestPayload['oauth_signature'] = $signature;

        $requestPayloadString = $normalizer->normalize($requestPayload);


        return $requestBuilder->buildRequest($requestPayloadString);
    }

    private function buildPayload(): array
    {
        $this->time = time();

        echo $this->time . PHP_EOL;

        return
            [
                ProctorioConfig::LAUNCH_URL => ProctorioConfig::PROCTORIO_URL,
                //'https://qa.eu.preprod.premium.taocloud.org/ltiDeliveryProvider/DeliveryTool/launch/eyJkZWxpdmVyeSI6Imh0dHBzOlwvXC9sdXRwcjAxb2F4LmV1LnByZW1pdW0udGFvY2xvdWQub3JnXC8jaTE1ODA0ODc3NzAyOTg5MjIzIn0=',
                ProctorioConfig::USER_ID => 'mike123456',
                ProctorioConfig::OAUTH_CONSUMER_KEY => self::TESTING_KEY,
                ProctorioConfig::EXAM_START => 'https://proctorio.com/customers',
                //'https://qa.eu.preprod.premium.taocloud.org/ltiDeliveryProvider/DeliveryTool/launch/eyJkZWxpdmVyeSI6Imh0dHBzOlwvXC9sdXRwcjAxb2F4LmV1LnByZW1pdW0udGFvY2xvdWQub3JnXC8jaTE1ODA0ODc3NzAyOTg5MjIzIn0=',
                ProctorioConfig::EXAM_TAKE => 'https://proctorio.com/about',
//                    'https://qa.eu.preprod.premium.taocloud.org/taoDelivery/DeliveryServer/runDeliveryExecution?deliveryExecution=kve_de_https%3A%2F%2Flutpr01oax.eu.premium.taocloud.org%2F%23i158453786611691892',
                ProctorioConfig::EXAM_END => 'https://proctorio.com/platform',
//                    'https://qa.eu.preprod.premium.taocloud.org/taoDelivery/DeliveryServer/index',
                ProctorioConfig::EXAM_SETTINGS => 'webtraffic',
                //'fullscreenmoderate,notabs',
                ProctorioConfig::FULL_NAME => 'name',//there might be an issue with spaces inside the string
                ProctorioConfig::EXAM_TAG => 'tag',
                //'oatsa-testing-TAG',
                ProctorioConfig::OAUTH_TIMESTAMP => $this->time,
                ProctorioConfig::OAUTH_NONCE => 'mike123456nounce12',
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