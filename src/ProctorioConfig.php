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

use oat\Proctorio\Exception\ProctorioParameterException;
use Ramsey\Uuid\Uuid;

class ProctorioConfig
{
    public const PROCTORIO_URL = 'https://%s' . '15499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8';
    public const CURRENT_DEFAULT_REGION = 'us';
    public const LAUNCH_URL = 'launch_url';
    public const USER_ID = 'user_id';
    public const OAUTH_CONSUMER_KEY = 'oauth_consumer_key';
    public const EXAM_START = 'exam_start';
    public const EXAM_TAKE = 'exam_take';
    public const EXAM_END = 'exam_end';
    public const EXAM_SETTINGS = 'exam_settings';
    public const FULL_NAME = 'fullname';
    public const EXAM_TAG = 'exam_tag';
    public const OAUTH_SIGNATURE_METHOD = 'oauth_signature_method';
    public const OAUTH_VERSION = 'oauth_version';
    public const OAUTH_TIMESTAMP = 'oauth_timestamp';
    public const OAUTH_NONCE = 'oauth_nonce';
    public const HMAC_SHA_1 = 'HMAC-SHA1';
    public const DEFAULT_OAUTH_VERSION = '1.0';
    public const POST_MANHOOD = 'POST';

    /**
     * Proctorio require array in specific order
     * We will remove unset config values if empty.
     * @throws ProctorioParameterException
     */
    public function configure(array $parameters): array
    {
        $fullOrderedParams = [
            self::LAUNCH_URL => $parameters[self::LAUNCH_URL],
            self::USER_ID => $parameters[self::USER_ID],
            self::OAUTH_CONSUMER_KEY => $parameters[self::OAUTH_CONSUMER_KEY],
            self::EXAM_START => $parameters[self::LAUNCH_URL],
            self::EXAM_TAKE => $parameters[self::EXAM_TAKE],
            self::EXAM_END => $this->getDefaultValue($parameters, self::EXAM_END),
            self::EXAM_SETTINGS => $this->getDefaultValue($parameters, self::EXAM_SETTINGS),
            self::EXAM_TAG => $parameters[self::EXAM_TAG] ?? null,
            self::FULL_NAME => $parameters[self::FULL_NAME] ?? null,
            self::OAUTH_SIGNATURE_METHOD => $this->getDefaultValue($parameters, self::OAUTH_SIGNATURE_METHOD, self::HMAC_SHA_1),
            self::OAUTH_VERSION => $this->getDefaultValue($parameters, self::OAUTH_VERSION, self::DEFAULT_OAUTH_VERSION),
            self::OAUTH_TIMESTAMP => $this->getDefaultValue($parameters, self::OAUTH_TIMESTAMP, (string)time()),
            self::OAUTH_NONCE => $this->getDefaultValue($parameters, self::OAUTH_NONCE, (string)Uuid::uuid4()),
        ];

        return $this->cleanEmptyNonMandatoryFields($fullOrderedParams);
    }

    private function getDefaultValue(array $parameters, string $field, string $default = ''): string
    {
        if (isset($parameters[$field])) {
            return (string)$parameters[$field];
        }
        return $default;
    }

    private function getNonMandatoryFields(): array
    {
        return [
            self::FULL_NAME,
            self::EXAM_TAG
        ];
    }


    private function cleanEmptyNonMandatoryFields(array $parameters): array
    {
        foreach ($this->getNonMandatoryFields() as $field)
        {
            if ($parameters[$field] === null) {
                unset($parameters[$field]);
            }
        }

        return $parameters;
    }
}
