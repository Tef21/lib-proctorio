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

namespace oat\Proctorio\tests\unit;

use oat\Proctorio\Exception\ProctorioParameterException;
use oat\Proctorio\ProctorioConfig;
use PHPUnit\Framework\TestCase;

class ProctorioConfigTest extends TestCase
{
    private const LAUNCH_URL_CUSTOM_VALUE = 'launch_url_custom';
    private const USER_ID_CUSTOM_VALUE = 'user_id_custom';
    private const OAUTH_CONSUMER_KEY_CUSTOM_VALUE = 'oauth_consumer_key_custom';
    private const EXAM_START_CUSTOM_VALUE = 'exam_start_custom';
    private const EXAM_TAKE_CUSTOM_VALUE = 'exam_take_custom';
    private const EXAM_END_CUSTOM_VALUE = 'exam_end_custom';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT = 'recordaudio';
    private const EXAM_SETTINGS_CUSTOM_ELEMENT_2 = 'recordvideo';
    private const EXAM_SETTINGS_CUSTOM_VALUE = [
        self::EXAM_SETTINGS_CUSTOM_ELEMENT,
        self::EXAM_SETTINGS_CUSTOM_ELEMENT_2
    ];
    private const EXAM_SETTINGS_EXPECTED_VALUE = 'recordaudio,recordvideo';
    private const FULL_NAME_CUSTOM_VALUE = 'full_name_custom';
    private const EXAM_TAG_CUSTOM_VALUE = 'exam_tag_custom';
    private const OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE = 'HMAC-SHA1';
    private const OAUTH_VERSION_CUSTOM_VALUE = '1.0';
    private const OAUTH_TIMESTAMP_CUSTOM_VALUE = '1586522823';
    private const OAUTH_NONCE_CUSTOM_VALUE = 'oauth_nonce_custom';

    /** @var ProctorioConfig */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new ProctorioConfig();
    }

    public function testConfigureCustomValues(): void
    {
        $result = $this->subject->configure($this->getCustomParameters(), self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->checkIfAllKeysArePresent($result);

        $this->assertEquals($result[ProctorioConfig::LAUNCH_URL], self::LAUNCH_URL_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::USER_ID], self::USER_ID_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_CONSUMER_KEY], self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_START], self::EXAM_START_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_TAKE], self::EXAM_TAKE_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_END], self::EXAM_END_CUSTOM_VALUE);
        $this->assertIsString($result[ProctorioConfig::EXAM_SETTINGS]);
        $this->assertEquals($result[ProctorioConfig::EXAM_SETTINGS], self::EXAM_SETTINGS_EXPECTED_VALUE);
        $this->assertEquals($result[ProctorioConfig::FULL_NAME], self::FULL_NAME_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_TAG], self::EXAM_TAG_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_SIGNATURE_METHOD], self::OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_VERSION], self::OAUTH_VERSION_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_TIMESTAMP], self::OAUTH_TIMESTAMP_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_NONCE], self::OAUTH_NONCE_CUSTOM_VALUE);
    }

    public function testConfigureArrayWhenTagEmpty(): void
    {
        $params = $this->getCustomParameters();
        unset($params[ProctorioConfig::FULL_NAME]);
        $result = $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertSame(self::EXAM_TAG_CUSTOM_VALUE, $result[ProctorioConfig::EXAM_TAG]);
        $this->assertFalse(isset($result[ProctorioConfig::FULL_NAME]));
    }

    public function testConfigureDefaultValues(): void
    {
        $result = $this->subject->configure([
            ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
            ProctorioConfig::USER_ID => self::OAUTH_NONCE_CUSTOM_VALUE,
            ProctorioConfig::EXAM_START => self::EXAM_START_CUSTOM_VALUE,
            ProctorioConfig::EXAM_TAKE => self::EXAM_TAKE_CUSTOM_VALUE,
            ProctorioConfig::EXAM_SETTINGS => self::EXAM_SETTINGS_CUSTOM_VALUE,
            ProctorioConfig::EXAM_END => self::EXAM_END_CUSTOM_VALUE
        ], self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);

        $this->assertEquals(ProctorioConfig::HMAC_SHA_1, $result[ProctorioConfig::OAUTH_SIGNATURE_METHOD]);
        $this->assertEquals(ProctorioConfig::DEFAULT_OAUTH_VERSION, $result[ProctorioConfig::OAUTH_VERSION]);
    }

    public function testConfigureReturnArrayInRightOrder(): void
    {
        $result = $this->subject->configure($this->getCustomParameters(), self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertSame($this->getRequiredOrder(), array_keys($result));
    }

    public function testConfigureWithMissingParameters()
    {
        $params = $this->getCustomParameters();
        unset($params[ProctorioConfig::LAUNCH_URL]);
        $this->expectException(ProctorioParameterException::class);
        $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
    }

    public function testConfigureWithMissingOptionalParameters()
    {
        $params = $this->getCustomParameters();
        unset($params[ProctorioConfig::FULL_NAME]);
        $result = $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertArrayNotHasKey(ProctorioConfig::FULL_NAME, $result);
    }

    public function testConfigureWithMissingEmptyOptionalParameters()
    {
        $params = $this->getCustomParameters();
        $params[ProctorioConfig::FULL_NAME] = '';
        $result = $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertArrayNotHasKey(ProctorioConfig::FULL_NAME, $result);
    }

    public function testConfigureWithExamSettingsAsString()
    {
        $params = $this->getCustomParameters();
        $params[ProctorioConfig::EXAM_SETTINGS] = 'string';
        $this->expectException(ProctorioParameterException::class);
        $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
    }

    public function testConfigureWithExamSettingsWithIllegalStrings()
    {
        $params = $this->getCustomParameters();
        $params[ProctorioConfig::EXAM_SETTINGS] = [
            self::EXAM_SETTINGS_CUSTOM_ELEMENT,
            self::EXAM_SETTINGS_CUSTOM_ELEMENT_2
        ];
        $result = $this->subject->configure($params, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertSame('recordaudio,recordvideo', $result[ProctorioConfig::EXAM_SETTINGS]);
    }

    private function getCustomParameters(): array
    {
        return [
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
    }

    private function checkIfAllKeysArePresent(array $result): void
    {
        $this->assertArrayHasKey(ProctorioConfig::LAUNCH_URL, $result);
        $this->assertArrayHasKey(ProctorioConfig::USER_ID, $result);
        $this->assertArrayHasKey(ProctorioConfig::OAUTH_CONSUMER_KEY, $result);
        $this->assertArrayHasKey(ProctorioConfig::EXAM_START, $result);
        $this->assertArrayHasKey(ProctorioConfig::EXAM_TAKE, $result);
        $this->assertArrayHasKey(ProctorioConfig::EXAM_END, $result);
        $this->assertArrayHasKey(ProctorioConfig::EXAM_SETTINGS, $result);
        $this->assertArrayHasKey(ProctorioConfig::FULL_NAME, $result);
        $this->assertArrayHasKey(ProctorioConfig::EXAM_TAG, $result);
        $this->assertArrayHasKey(ProctorioConfig::OAUTH_SIGNATURE_METHOD, $result);
        $this->assertArrayHasKey(ProctorioConfig::OAUTH_VERSION, $result);
        $this->assertArrayHasKey(ProctorioConfig::OAUTH_TIMESTAMP, $result);
        $this->assertArrayHasKey(ProctorioConfig::OAUTH_NONCE, $result);
    }

    private function getRequiredOrder(): array
    {
        return [
            0 => 'launch_url',
            1 => 'user_id',
            2 => 'oauth_consumer_key',
            3 => 'exam_start',
            4 => 'exam_take',
            5 => 'exam_end',
            6 => 'exam_settings',
            7 => 'fullname',
            8 => 'exam_tag',
            9 => 'oauth_signature_method',
            10 => 'oauth_version',
            11 => 'oauth_timestamp',
            12 => 'oauth_nonce',
        ];
    }
}
