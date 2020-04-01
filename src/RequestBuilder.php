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

use oat\Proctorio\Exception\CurlExecutionException;

class RequestBuilder
{
    /** @var string $url */
    private $url;

    /** @var false|resource */
    private $ch;

    /**
     * RequestBuilder constructor.
     *
     * @param string $url
     */
    public function __construct(string $url = null)
    {
        $this->url = $url;
        $this->ch = curl_init();
    }

    public function buildRequest($payload): bool
    {
        return curl_setopt_array($this->ch, [
            CURLOPT_URL => $this->getUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded'],
        ]);
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url ?? ProctorioConfig::getProctorioDefaultUrl();
    }

    /**
     * @return bool|resource
     * @throws CurlExecutionException
     */
    public function execute()
    {
        if (!$this->ch) {
            throw new CurlExecutionException('You have to build request first');
        }
        $result = curl_exec($this->ch);

        if (curl_errno($this->ch)) {
            throw new CurlExecutionException(curl_error($this->ch));
        }

        curl_close($this->ch);

        return $result;
    }
}
