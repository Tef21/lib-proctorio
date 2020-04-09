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
use Psr\Http\Message\ResponseInterface;

class ProctorioRequestHandler
{
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
        $this->httpClient = $httpClient;

        if ($this->httpClient === null) {
            $this->httpClient = new Client();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function execute(string $payload): ResponseInterface
    {
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

        return $this->httpClient->send($request);
    }
}
