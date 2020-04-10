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

use Exception;
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

    private const MANDATORY_FIELD = 'mandatory';

    /**
     * Proctorio require array in specific order
     * Each parameter may have default value
     * Default value will be set when no field param provided
     * When parameter is not set and
     * it doesn't have default value and is not mandatory
     * it will be omitted
     *
     * @throws ProctorioParameterException
     * @throws Exception
     */
    private function getProctorioOrderedParams(): array
    {
        return [
            self::LAUNCH_URL => self::MANDATORY_FIELD,
            self::USER_ID => self::MANDATORY_FIELD,
            self::OAUTH_CONSUMER_KEY => self::MANDATORY_FIELD,
            self::EXAM_START => self::MANDATORY_FIELD,
            self::EXAM_TAKE => self::MANDATORY_FIELD,
            self::EXAM_END => self::MANDATORY_FIELD,
            self::EXAM_SETTINGS => self::MANDATORY_FIELD,
            self::FULL_NAME => null,
            self::EXAM_TAG => null,
            self::OAUTH_SIGNATURE_METHOD => self::HMAC_SHA_1,
            self::OAUTH_VERSION => self::DEFAULT_OAUTH_VERSION,
            self::OAUTH_TIMESTAMP => (string) time(),
            self::OAUTH_NONCE => Uuid::uuid4()->toString(),
        ];
    }

    /**
     * @throws ProctorioParameterException
     */
    private function createOrderedParamteres(array $parameters): array
    {
        $proctorioParameters = [];
        foreach ($this->getProctorioOrderedParams() as $paramName => $default) {
            if ($default === self::MANDATORY_FIELD && !isset($parameters[$paramName])) {
                throw new ProctorioParameterException(
                    sprintf('Mandatory field %s missing', $paramName)
                );
            }

            if ($paramName === ProctorioConfig::EXAM_SETTINGS) {
                if (!is_array($parameters[$paramName])) {
                    throw new ProctorioParameterException('exam_settings has to be array');
                }

                $proctorioParameters[$paramName] = implode(',', array_map('trim', $parameters[$paramName]));
                continue;
            }

            if (isset($parameters[$paramName])) {
                $proctorioParameters[$paramName] = $parameters[$paramName];
                continue;
            }

            if ($default !== null) {
                $proctorioParameters[$paramName] = $default;
            }
        }

        return $proctorioParameters;
    }

    /**
     * @return string[]
     * @throws ProctorioParameterException
     */
    public function configure(array $parameters, string $key): array
    {
        $parameters[self::OAUTH_CONSUMER_KEY] = $key;
        return $this->createOrderedParamteres($parameters);
    }
}
