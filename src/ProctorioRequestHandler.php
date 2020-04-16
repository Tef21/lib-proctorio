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

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use oat\Proctorio\Exception\InvalidProctorioResponseException;
use oat\Proctorio\Response\ProctorioResponse;

class ProctorioRequestHandler
{
    private const RESPONSE_CODES = [
        2653 => 'Missing required parameters',
        2654 => 'Invalid parameter',
        2655 => 'Incorrect consumer key',
        2656 => 'Signature is invalid',
        2657 => 'The used timestamp is out of range',
        2658 => 'Invalid exam tag ID',
        2659 => 'Invalid settings',
        2660 => 'Unknown'
    ];

    /** @var string $url */
    private $url;

    /** @var ClientInterface */
    private $httpClient;

    /**
     * RequestBuilder constructor.
     */
    public function __construct(string $url, ClientInterface $httpClient = null)
    {
        $this->url = $url;
        $this->httpClient = $httpClient ?? new Client();
    }

    /**
     * @throws InvalidProctorioResponseException
     */
    public function execute(string $payload): ProctorioResponse
    {
        try {
            $request = new Request(
                'POST',
                $this->url,
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    ],
                ],
                $payload
            );

            $response = (string)$this->httpClient
                ->send($request)
                ->getBody();

            $responseParts = $this->getResponseParts($response);

            return new ProctorioResponse(
                $responseParts[0],
                $responseParts[1]
            );
        } catch (GuzzleException $exception) {
            throw new InvalidProctorioResponseException(
                'Invalid Proctorio response',
                $exception->getMessage()
            );
        }
    }

    /**
     * @throws InvalidProctorioResponseException
     */
    private function getResponseParts(string $response): array
    {
        $data = json_decode($response, true);

        if (count($data) !== 2) {
            throw new InvalidProctorioResponseException(
                'Invalid Proctorio response',
                $response
            );
        }

        $firstResponsePart = current($data);

        if (in_array($firstResponsePart, array_keys(self::RESPONSE_CODES), true)) {
            throw new InvalidProctorioResponseException(
                self::RESPONSE_CODES[$firstResponsePart],
                $response
            );
        }

        return $data;
    }
}
