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
use oat\Proctorio\ProctorioConfigValidator;
use oat\Proctorio\ProctorioService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProctorioServiceTest extends TestCase
{
    private const LAUNCH_URL_CUSTOM_VALUE = 'launch_url_custom';
    private const OAUTH_CONSUMER_KEY_CUSTOM_VALUE = 'oauth_consumer_key_custom';
    private const USER_ID_CUSTOM_VALUE = 'user_id_custom';
    private const EXAM_TAKE_CUSTOM_VALUE = 'exam_take_custom';

    private const CONFIG_EXAMPLE = [
        ProctorioConfig::LAUNCH_URL  => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::USER_ID  => self::USER_ID_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_CONSUMER_KEY  => self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE,
        ProctorioConfig::EXAM_START  => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAKE  => self::EXAM_TAKE_CUSTOM_VALUE,
        ProctorioConfig::EXAM_END  => '',
        ProctorioConfig::EXAM_SETTINGS  => '',
        ProctorioConfig::FULL_NAME  => 'name',
        ProctorioConfig::EXAM_TAG  => 'tag',
        ProctorioConfig::OAUTH_SIGNATURE_METHOD  => 'HMAC-SHA1',
        ProctorioConfig::OAUTH_VERSION  => '1.0',
        ProctorioConfig::OAUTH_TIMESTAMP  => 'time',
        ProctorioConfig::OAUTH_NONCE => 'nonce',
    ];
    private const SECRET = 'secret';
    private const PARAMS_EXAMPLE = [
        ProctorioConfig::LAUNCH_URL => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::USER_ID => self::USER_ID_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_CONSUMER_KEY => self::OAUTH_CONSUMER_KEY_CUSTOM_VALUE,
        ProctorioConfig::EXAM_START => self::LAUNCH_URL_CUSTOM_VALUE,
        ProctorioConfig::EXAM_TAKE => self::EXAM_TAKE_CUSTOM_VALUE,
        ProctorioConfig::OAUTH_TIMESTAMP  => 'time',
        ProctorioConfig::OAUTH_NONCE => 'nonce',
    ];

    /** @var ProctorioAccessProvider|MockObject */
    private $proctorioProviderMock;

    /** @var ProctorioService */
    private $subject;

    /** @var ProctorioConfig|MockObject */
    private $validatorMock;


    protected function setUp(): void
    {
        $this->proctorioProviderMock = $this->createMock(ProctorioAccessProvider::class);
        $this->validatorMock = $this->createMock(ProctorioConfigValidator::class);
        $this->subject = new ProctorioService(
            $this->proctorioProviderMock,
            $this->validatorMock
        );
    }

    public function testCallRemoteProctoring(): void
    {
        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with(self::PARAMS_EXAMPLE);
        $this->proctorioProviderMock->expects($this->once())
            ->method('retrieve')
            ->with(self::CONFIG_EXAMPLE, self::SECRET)
            ->willReturn('string');
        $this->subject->callRemoteProctoring(self::PARAMS_EXAMPLE, self::SECRET);
    }
}
