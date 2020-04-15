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

use oat\Proctorio\Config\Validator\ProctorioConfigValidator;
use oat\Proctorio\Config\Validator\ValidatorInterface;
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
    public const VALID_EXAM_SETTINGS = [
        'recordvideo',
        'recordaudio',
        'recordscreen',
        'recordwebtraffic',
        'recordroomstart',
        'verifyvideo',
        'verifyaudio',
        'verifydesktop',
        'verifyidauto',
        'verifyidlive',
        'verifysignature',
        'fullscreenlenient',
        'fullscreenmoderate',
        'fullscreensevere',
        'clipboard',
        'notabs',
        'linksonly',
        'closetabs',
        'onescreen',
        'print',
        'downloads',
        'cache',
        'rightclick',
        'noreentry',
        'agentreentry',
        'calculatorbasic',
        'calculatorsci',
        'whiteboard'
    ];
    private const ORDERED_PARAMS = [
        self::LAUNCH_URL,
        self::USER_ID,
        self::OAUTH_CONSUMER_KEY,
        self::EXAM_START,
        self::EXAM_TAKE,
        self::EXAM_END,
        self::EXAM_SETTINGS,
        self::FULL_NAME,
        self::EXAM_TAG,
        self::OAUTH_SIGNATURE_METHOD,
        self::OAUTH_VERSION,
        self::OAUTH_TIMESTAMP,
        self::OAUTH_NONCE,
    ];

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ProctorioConfigValidator $validator = null)
    {
        $this->validator = $validator ?? new ProctorioConfigValidator(...[]);
    }

    /**
     * @return string[]
     * @throws ProctorioParameterException
     */
    public function configure(array $parameters, string $key): array
    {
        $parameters = $this->configDefaultParameters($parameters);
        $parameters[self::OAUTH_CONSUMER_KEY] = $key;
        $parameters = $this->sortParameters($parameters);

        $this->validator->validate($parameters);

        return $this->prepareParametersValues($parameters);
    }

    private function prepareParametersValues(array $parameters): array
    {
        $parameters[self::EXAM_SETTINGS] = implode(
            ',',
            array_map(
                'trim',
                $parameters[self::EXAM_SETTINGS]
            )
        );

        return array_filter($parameters);
    }

    private function sortParameters(array $parameters): array
    {
        $orderedParameters = [];

        foreach (self::ORDERED_PARAMS as $key) {
            $orderedParameters[$key] = $parameters[$key] ?? null;
        }

        return $orderedParameters;
    }

    private function configDefaultParameters(array $parameters): array
    {
        return array_replace_recursive(
            [
                self::OAUTH_NONCE => Uuid::uuid4()->toString(),
                self::OAUTH_SIGNATURE_METHOD => ProctorioConfig::HMAC_SHA_1,
                self::OAUTH_TIMESTAMP => (string)time(),
                self::OAUTH_VERSION => ProctorioConfig::DEFAULT_OAUTH_VERSION,
            ],
            array_filter($parameters)
        );
    }
}
