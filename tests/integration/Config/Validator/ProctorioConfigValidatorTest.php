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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA;
 */

declare(strict_types=1);

namespace oat\Proctorio\tests\integration\Config\Validator;

use oat\Proctorio\Config\Validator\ProctorioConfigValidator;
use oat\Proctorio\Exception\ProctorioParameterException;
use oat\Proctorio\ProctorioConfig;
use PHPUnit\Framework\TestCase;

class ProctorioConfigValidatorTest extends TestCase
{
    private const LAUNCH_URL_CUSTOM_VALUE = 'https://launch_url_custom';
    private const USER_ID_CUSTOM_VALUE = 'user_id_custom';
    private const EXAM_START_CUSTOM_VALUE = 'https://exam_start_custom';
    private const EXAM_TAKE_CUSTOM_VALUE = 'https://exam_take_custom';
    private const EXAM_END_CUSTOM_VALUE = 'https://exam_end_custom';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT = 'recordaudio';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT_2 = 'recordvideo';
    private const EXAM_SETTINGS_CUSTOM_VALUE = [
        self::EXAM_SETTINGS_CUSTOM_ELEMENT,
        self::EXAM_SETTINGS_CUSTOM_ELEMENT_2
    ];
    private const FULL_NAME_CUSTOM_VALUE = 'full_name_custom';
    private const EXAM_TAG_CUSTOM_VALUE = 'exam_tag_custom';
    private const OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE = ProctorioConfig::HMAC_SHA_1;
    private const OAUTH_VERSION_CUSTOM_VALUE = ProctorioConfig::DEFAULT_OAUTH_VERSION;
    private const OAUTH_TIMESTAMP_CUSTOM_VALUE = '1586522809';
    private const OAUTH_NONCE_CUSTOM_VALUE = 'oauth_nonce_custom';

    private const DEFAULT_VALUES = [
        ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::USER_ID => self::USER_ID_CUSTOM_VALUE,
        ProctorioConfig::EXAM_START => self::EXAM_START_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAKE => self::EXAM_TAKE_CUSTOM_VALUE,
        ProctorioConfig::EXAM_END => self::EXAM_END_CUSTOM_VALUE,
        ProctorioConfig::EXAM_SETTINGS => self::EXAM_SETTINGS_CUSTOM_VALUE,
        ProctorioConfig::FULL_NAME => self::FULL_NAME_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAG => self::EXAM_TAG_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_SIGNATURE_METHOD => self::OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_VERSION => self::OAUTH_VERSION_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_TIMESTAMP => self::OAUTH_TIMESTAMP_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_NONCE => self::OAUTH_NONCE_CUSTOM_VALUE,
    ];

    /** @var ProctorioConfigValidator */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new ProctorioConfigValidator();
    }

    /**
     * @param string $expectedErrorMessage
     * @param string $configName
     * @param mixed $configValue
     *
     * @throws ProctorioParameterException
     *
     * @dataProvider invalidConfigProvider
     */
    public function testValidateInvalidParameters(string $expectedErrorMessage, string $configName, $configValue): void
    {
        $this->expectException(ProctorioParameterException::class);
        $this->expectExceptionMessage($expectedErrorMessage);

        $this->subject->validate(
            array_replace_recursive(
                self::DEFAULT_VALUES,
                [
                    $configName => $configValue
                ]
            )
        );
    }

    public function invalidConfigProvider(): array
    {
        return [
            [
                'launch_url: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::LAUNCH_URL,
                null,
            ],
            [
                'launch_url: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::LAUNCH_URL,
                'https://' . str_repeat('-', 493),
            ],
            [
                'launch_url: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::LAUNCH_URL,
                'http://',
            ],
            [
                'launch_url: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::LAUNCH_URL,
                'http\:\/\/',
            ],
            [
                'user_id: parameter is a required string with max 36 characters',
                ProctorioConfig::USER_ID,
                null,
            ],
            [
                'user_id: parameter is a required string with max 36 characters',
                ProctorioConfig::USER_ID,
                str_repeat('-', 37),
            ],
            [
                'exam_start: parameter must contain a valid https url',
                ProctorioConfig::EXAM_START,
                null,
            ],
            [
                'exam_start: parameter must contain a valid https url',
                ProctorioConfig::EXAM_START,
                'http://',
            ],
            [
                'exam_start: parameter must contain a valid https url',
                ProctorioConfig::EXAM_START,
                'http\:\/\/',
            ],
            [
                'exam_start: parameter must contain a valid https url',
                ProctorioConfig::EXAM_START,
                'https://' . str_repeat('-', 493),
            ],
            [
                'exam_take: parameter must contain a valid https url with max 1000 characters',
                ProctorioConfig::EXAM_TAKE,
                null,
            ],
            [
                'exam_take: parameter must contain a valid https url with max 1000 characters',
                ProctorioConfig::EXAM_TAKE,
                'http://',
            ],
            [
                'exam_take: parameter must contain a valid https url with max 1000 characters',
                ProctorioConfig::EXAM_TAKE,
                'http\:\/\/',
            ],
            [
                'exam_take: parameter must contain a valid https url with max 1000 characters',
                ProctorioConfig::EXAM_TAKE,
                'https://' . str_repeat('-', 993),
            ],
            [
                'exam_end: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::EXAM_END,
                null,
            ],
            [
                'exam_end: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::EXAM_END,
                'http://',
            ],
            [
                'exam_end: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::EXAM_END,
                'http\:\/\/',
            ],
            [
                'exam_end: parameter must contain a valid https url with max 500 characters',
                ProctorioConfig::EXAM_END,
                'https://' . str_repeat('-', 493),
            ],
            [
                'exam_settings: parameter has to be array',
                ProctorioConfig::EXAM_SETTINGS,
                'it should be an array',
            ],
            [
                'exam_settings: parameter has to be array with valid settings',
                ProctorioConfig::EXAM_SETTINGS,
                [
                    'not_allowed'
                ],
            ],
            [
                'oauth_consumer_key: parameter is a required string with max 32 characters',
                ProctorioConfig::OAUTH_CONSUMER_KEY,
                str_repeat('-', 33),
            ],
            [
                'oauth_signature_method: parameter supports only HMAC-SHA1',
                ProctorioConfig::OAUTH_SIGNATURE_METHOD,
                'HMAC-SHA2',
            ],
            [
                'oauth_version: parameter must be 1.0',
                ProctorioConfig::OAUTH_VERSION,
                '3.0',
            ],
            [
                'oauth_timestamp: parameter must be numeric',
                ProctorioConfig::OAUTH_TIMESTAMP,
                'abc',
            ],
            [
                'oauth_nonce: parameter is a required string',
                ProctorioConfig::OAUTH_NONCE,
                '',
            ],
            [
                'fullname: parameter is a required string with max 100 characters',
                ProctorioConfig::FULL_NAME,
                str_repeat('-', 101),
            ],
            [
                'exam_tag: parameter is a required string with max 100 characters',
                ProctorioConfig::EXAM_TAG,
                str_repeat('-', 101),
            ],
        ];
    }

    /**
     * @throws ProctorioParameterException
     */
    public function testMissingRegisterValidator(): void
    {
        $this->expectException(ProctorioParameterException::class);
        $this->expectExceptionMessage('There is no validator for config [invalid]');

        $this->subject->validate(['invalid' => 0]);
    }
}
