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
    private const EXAM_SETTINGS_CUSTOM_VALUE = 'exam_settings_custom';
    private const FULL_NAME_CUSTOM_VALUE = 'full_name_custom';
    private const EXAM_TAG_CUSTOM_VALUE = 'exam_tag_custom';
    private const OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE = 'oauth_signature_method_custom';
    private const OAUTH_VERSION_CUSTOM_VALUE = 'oauth_version_custom';
    private const OAUTH_TIMESTAMP_CUSTOM_VALUE = 'oauth_timestamp_custom';
    private const OAUTH_NONCE_CUSTOM_VALUE = 'oauth_nonce_custom';

    /** @var ProctorioConfig */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new ProctorioConfig();
    }

    private function getCustomParameters(): array
    {
        return [
            ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
            ProctorioConfig::USER_ID => self::USER_ID_CUSTOM_VALUE,
            ProctorioConfig::OAUTH_CONSUMER_KEY => self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE,
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

    public function testConfigureCustomValues(): void
    {

        $result = $this->subject->configure($this->getCustomParameters());
        $this->checkIfAllKeysArePresent($result);

        $this->assertEquals($result[ProctorioConfig::LAUNCH_URL], self::LAUNCH_URL_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::USER_ID], self::USER_ID_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_CONSUMER_KEY], self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_START], self::LAUNCH_URL_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_TAKE], self::EXAM_TAKE_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_END], self::EXAM_END_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_SETTINGS], self::EXAM_SETTINGS_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::FULL_NAME], self::FULL_NAME_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::EXAM_TAG], self::EXAM_TAG_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_SIGNATURE_METHOD], self::OAUTH_SIGNATURE_METHOD_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_VERSION], self::OAUTH_VERSION_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_TIMESTAMP], self::OAUTH_TIMESTAMP_CUSTOM_VALUE);
        $this->assertEquals($result[ProctorioConfig::OAUTH_NONCE], self::OAUTH_NONCE_CUSTOM_VALUE);
    }

    public function testConfigureArrayWhenTagEmpty()
    {
        $params = $this->getCustomParameters();
        unset($params[ProctorioConfig::FULL_NAME]);
        $result = $this->subject->configure($params);
        $this->assertSame(self::EXAM_TAG_CUSTOM_VALUE ,$result[ProctorioConfig::EXAM_TAG]);
        $this->assertFalse(isset($result[ProctorioConfig::FULL_NAME]));
    }

    public function testConfigureDefaultValues(): void
    {
        $result = $this->subject->configure([
            ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
            ProctorioConfig::USER_ID => self::OAUTH_NONCE_CUSTOM_VALUE,
            ProctorioConfig::OAUTH_CONSUMER_KEY => self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE,
            ProctorioConfig::EXAM_START => self::EXAM_START_CUSTOM_VALUE,
            ProctorioConfig::EXAM_TAKE => self::EXAM_TAKE_CUSTOM_VALUE,
        ]);

        $this->assertEquals(ProctorioConfig::HMAC_SHA_1, $result[ProctorioConfig::OAUTH_SIGNATURE_METHOD]);
        $this->assertEquals(ProctorioConfig::DEFAULT_OAUTH_VERSION, $result[ProctorioConfig::OAUTH_VERSION]);
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
}
