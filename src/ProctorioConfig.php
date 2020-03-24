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

use Ramsey\Uuid\Uuid;

class ProctorioConfig
{

    // https://{account region}5499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8
    public const PROCTORIO_URL = 'https://us15499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8';
//    public const PROCTORIO_URL = 'https://{areaofexams}15499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8';
//    public const PROCTORIO_URL = 'https://premium.docker.localhost/proctorio/test.php';

    //HTTPS must be used when generating the URLs
    //Only TLS 1.2 and 1.3 are supported.
    //The signature base string is used to generate the request signing key.
    // Proctorio uses percent encoding based strictly on RFC3986.
    //mandatory

    public const LAUNCH_URL = 'launch_url';
    //max length = 500
    //mandatory

    public const USER_ID = 'user_id';
    //alphanumeric (hyphens    //also acceptable)
    //max length = 36
    //mandatory

    public const OAUTH_CONSUMER_KEY = 'oauth_consumer_key';
    // max length = 32
    //mandatory

    public const EXAM_START = 'exam_start';
    // max length = 500
    //mandatory

    public const EXAM_TAKE = 'exam_take';
    // max length = 1000
    //mandatory

    public const EXAM_END = 'exam_end';
    // max length = 500
    //mandatory

    public const EXAM_SETTINGS = 'exam_settings';
    //mandatory

    public const FULL_NAME = 'fullname';
    // max length = 100

    public const EXAM_TAG = 'exam_tag';
    // max length = 100

    public const OAUTH_SIGNATURE_METHOD = 'oauth_signature_method';
    // HMAC-SHA1
    // mandatory

    public const OAUTH_VERSION = 'oauth_version';
    // 1.0 
    // mandatory

    public const OAUTH_TIMESTAMP = 'oauth_timestamp';
    //Epoch timestamp. Used to prevent  delayed attacks.
    // Must be within 8 minutes  of the correct time, otherwise, it is  rejected. 
    //mandatory

    public const OAUTH_NONCE = 'oauth_nonce';
    // Anything unique. Used to prevent replay  attacks 
    // mandatory

    public const HMAC_SHA_1 = 'HMAC-SHA1';
    //default value for OAUTH_SIGNATURE_METHOD

    public const DEFAULT_OAUTH_VERSION = '1.0';

    //default value for OAUTH_VERSION


    public function configure(array $parameters): array
    {
        return [
            self::LAUNCH_URL => $this->getDefaultValue($parameters, self::LAUNCH_URL, self::PROCTORIO_URL),
            self::USER_ID => $this->getDefaultValue($parameters, self::USER_ID),
            self::OAUTH_CONSUMER_KEY => $this->getDefaultValue($parameters, self::OAUTH_CONSUMER_KEY),
            self::EXAM_START => $this->getDefaultValue($parameters, self::EXAM_START),
            self::EXAM_TAKE => $this->getDefaultValue($parameters, self::EXAM_TAKE),
            self::EXAM_END => $this->getDefaultValue($parameters, self::EXAM_END),
            self::EXAM_SETTINGS => $this->getDefaultValue($parameters, self::EXAM_SETTINGS),
            self::FULL_NAME => $this->getDefaultValue($parameters, self::FULL_NAME),
            self::EXAM_TAG => $this->getDefaultValue($parameters, self::EXAM_TAG),

            self::OAUTH_SIGNATURE_METHOD => $this->getDefaultValue($parameters, self::OAUTH_SIGNATURE_METHOD, self::HMAC_SHA_1),
            self::OAUTH_VERSION => $this->getDefaultValue($parameters, self::OAUTH_VERSION, self::DEFAULT_OAUTH_VERSION),
            self::OAUTH_TIMESTAMP => $this->getDefaultValue($parameters, self::OAUTH_TIMESTAMP, time()),
            self::OAUTH_NONCE => $this->getDefaultValue($parameters, self::OAUTH_NONCE, Uuid::uuid4()),
        ];
    }

    private function getDefaultValue(array $parameters, $field, $default = '')
    {
        return $parameters[$field] ?? $default;
    }
}
