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
        $signatureBaseString = $this->createSignatureBaseString($encoder, $normalizer, $requestPayload);
        $requestPayload['oauth_signature'] = $signatureBaseString;

        $requestPayloadString = $normalizer->normalize($requestPayload);


        return $requestBuilder->buildRequest($requestPayloadString);
    }

    private function buildPayload()
    {
        $this->time = time();
        return
            [
                ProctorioConfig::LAUNCH_URL => 'https://qa.eu.preprod.premium.taocloud.org/tao/Main/login',
                ProctorioConfig::USER_ID => 'mike' . number_format(microtime(true), 0, '', ''),
                ProctorioConfig::OAUTH_CONSUMER_KEY => self::TESTING_KEY,
                ProctorioConfig::EXAM_START => 'https://qa.eu.preprod.premium.taocloud.org/tao/Main/login',
                ProctorioConfig::EXAM_TAKE => 'https://qa.eu.preprod.premium.taocloud.org/taoDelivery/DeliveryServer/runDeliveryExecution?deliveryExecution=kve_de_https%3A%2F%2Flutpr01oax.eu.premium.taocloud.org%2F%23i158453786611691892',
                ProctorioConfig::EXAM_END => 'https://qa.eu.preprod.premium.taocloud.org/taoDelivery/DeliveryServer/index',
                ProctorioConfig::EXAM_SETTINGS => 'webtraffic,recordvideo',
                ProctorioConfig::FULL_NAME => 'Mike OAT.SA',
                ProctorioConfig::EXAM_TAG => 'oatsa-testing-TAG',

                ProctorioConfig::OAUTH_TIMESTAMP => $this->time,
                ProctorioConfig::OAUTH_NONCE => sha1('mike' . number_format(microtime(true))),
            ];
    }

    private function getRequestBuilder(): RequestBuilder
    {
        return new RequestBuilder();
    }

    private function getProctorioConfig()
    {
        return new ProctorioConfig();
    }

    private function getEncoder()
    {
        return new Encoder();
    }

    private function getNormilizer()
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
    private function createSignatureBaseString(Encoder $encoder, Normalizer $normalizer, array $payload)
    {
        return (new SignatureBuilder())->buildSignature($encoder, $normalizer, $payload);
    }
}