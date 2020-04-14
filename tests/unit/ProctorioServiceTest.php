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
use oat\Proctorio\ProctorioAccessProvider;
use oat\Proctorio\ProctorioService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProctorioServiceTest extends TestCase
{
    private const OAUTH_CONSUMER_KEY_CUSTOM_VALUE = 'oauth_consumer_key_custom';
    private const USER_ID_CUSTOM_VALUE = 'user_id_custom';
    private const LAUNCH_URL_CUSTOM_VALUE = 'https://launch_url_custom';
    private const EXAM_END_CUSTOM_VALUE = 'https://exam_end_custom';
    private const EXAM_TAKE_CUSTOM_VALUE = 'https://exam_take_custom';
    private const EXAM_SETTINGS_EXAMPLE = [
        'recordaudio',
        'recordvideo'
    ];
    private const SECRET = 'secret';

    private const CONFIG_EXAMPLE = [
        ProctorioConfig::LAUNCH_URL  => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::USER_ID  => self::USER_ID_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_CONSUMER_KEY  => self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE,
        ProctorioConfig::EXAM_START  => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAKE  => self::EXAM_TAKE_CUSTOM_VALUE,
        ProctorioConfig::EXAM_END => self::EXAM_END_CUSTOM_VALUE,
        ProctorioConfig::EXAM_SETTINGS  => 'recordaudio,recordvideo',
        ProctorioConfig::OAUTH_SIGNATURE_METHOD  => 'HMAC-SHA1',
        ProctorioConfig::OAUTH_VERSION  => '1.0',
        ProctorioConfig::OAUTH_TIMESTAMP  => '1586522824',
        ProctorioConfig::OAUTH_NONCE => 'nonce',
    ];

    private const PARAMS_EXAMPLE = [
        ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::USER_ID => self::USER_ID_CUSTOM_VALUE,
        ProctorioConfig::EXAM_START => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::EXAM_END => self::EXAM_END_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAKE => self::EXAM_TAKE_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_TIMESTAMP  => '1586522824',
        ProctorioConfig::OAUTH_NONCE => 'nonce',
        ProctorioConfig::EXAM_SETTINGS  => self::EXAM_SETTINGS_EXAMPLE,
    ];

    /** @var ProctorioAccessProvider|MockObject */
    private $proctorioProviderMock;

    /** @var ProctorioService */
    private $subject;

    protected function setUp(): void
    {
        $this->proctorioProviderMock = $this->createMock(ProctorioAccessProvider::class);
        $this->subject = new ProctorioService(
            $this->proctorioProviderMock
        );
    }

    public function testCallRemoteProctoring(): void
    {
        $this->proctorioProviderMock->expects($this->once())
            ->method('retrieve')
            ->with(self::CONFIG_EXAMPLE, self::SECRET)
            ->willReturn('string');

        $this->subject->callRemoteProctoring(self::PARAMS_EXAMPLE, self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE, self::SECRET);
    }
}
