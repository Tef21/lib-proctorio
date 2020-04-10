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

namespace oat\Proctorio\tests\integration;

use oat\Proctorio\Config\ProctorioConfigValidator;
use oat\Proctorio\Exception\ProctorioParameterException;
use oat\Proctorio\ProctorioConfig;
use PHPUnit\Framework\TestCase;

class ProctorioConfigValidatorTest extends TestCase
{
    private const LAUNCH_URL_CUSTOM_VALUE = 'launch_url_custom';
    private const USER_ID_CUSTOM_VALUE = 'user_id_custom';
    private const EXAM_START_CUSTOM_VALUE = 'exam_start_custom';
    private const EXAM_TAKE_CUSTOM_VALUE = 'exam_take_custom';
    private const EXAM_END_CUSTOM_VALUE = 'exam_end_custom';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT = 'exam_settings_custom';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT_2 = 'exam_settings_custom_2';
    private const EXAM_SETTINGS_CUSTOM_VALUE = [
        self::EXAM_SETTINGS_CUSTOM_ELEMENT,
        self::EXAM_SETTINGS_CUSTOM_ELEMENT_2
    ];

    private const FULL_NAME_CUSTOM_VALUE = 'full_name_custom';
    private const EXAM_TAG_CUSTOM_VALUE = 'exam_tag_custom';
    private const OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE = 'oauth_signature_method_custom';
    private const OAUTH_VERSION_CUSTOM_VALUE = 'oauth_version_custom';
    private const OAUTH_TIMESTAMP_CUSTOM_VALUE = 'oauth_timestamp_custom';
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
            'all',
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
                'launch_url must be a required string with max 500 characters.',
                ProctorioConfig::LAUNCH_URL,
                null,
            ],
            [
                'launch_url must be a required string with max 500 characters.',
                ProctorioConfig::LAUNCH_URL,
                str_repeat('-', 501),
            ],
            [
                'user_id is required',
                ProctorioConfig::USER_ID,
                null,
            ],
            [
                'exam_start is required',
                ProctorioConfig::EXAM_START,
                null,
            ],
            [
                'exam_take is required',
                ProctorioConfig::EXAM_TAKE,
                null,
            ],
            [
                'exam_end is required',
                ProctorioConfig::EXAM_END,
                null,
            ],
            [
                'exam_settings has to be array',
                ProctorioConfig::EXAM_SETTINGS,
                'it should be an array',
            ],
            /*
            @TODO Validate missing fields
            [
                '...',
                ProctorioConfig::FULL_NAME,
                null,
            ],
            [
                '...',
                ProctorioConfig::EXAM_TAG,
                null,
            ],
            [
                '...',
                ProctorioConfig::OAUTH_VERSION,
                null,
            ],
            [
                '...',
                ProctorioConfig::OAUTH_TIMESTAMP,
                null,
            ],
            [
                '...',
                ProctorioConfig::OAUTH_NONCE,
                null,
            ],
            */
        ];
    }

    /**
     * @param string $configName
     * @param callable $expected
     *
     * @dataProvider defaultConfigProvider
     * @throws ProctorioParameterException
     */
    public function testValidateWillReturnDefaultValues(string $configName, callable $expected): void
    {
        $params = self::DEFAULT_VALUES;
        $params[$configName] = null;

        $config = $this->subject->validate('all', $params);

        $this->assertTrue($expected($config[$configName]));
    }

    public function defaultConfigProvider(): array
    {
        return [
            [
                ProctorioConfig::OAUTH_SIGNATURE_METHOD,
                function ($value) {
                    return $value === ProctorioConfig::HMAC_SHA_1;
                },
            ],
            [
                ProctorioConfig::OAUTH_TIMESTAMP,
                function ($value) {
                    return is_numeric($value);
                },
            ],
            [
                ProctorioConfig::OAUTH_NONCE,
                function ($value) {
                    return strlen($value) === 36;
                },
            ],
            [
                ProctorioConfig::OAUTH_VERSION,
                function ($value) {
                    return $value === ProctorioConfig::DEFAULT_OAUTH_VERSION;
                },
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

        $this->subject->validate('all', ['invalid' => 0]);
    }
}
