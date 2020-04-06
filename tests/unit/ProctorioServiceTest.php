<?php declare(strict_types=1);

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

/**
 * Created by PhpStorm.
 * User: bartlomiejmarszal
 * Date: 2020-04-01
 * Time: 16:10
 */

namespace oat\Proctorio\tests\unit;

use oat\Proctorio\ProctorioConfig;
use oat\Proctorio\ProctorioAccessProvider;
use oat\Proctorio\ProctorioService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProctorioServiceTest extends TestCase
{
    private const CONFIG_EXAMPLE = ['exampleKey' => 'exampleValue'];
    private const SECRET = 'secret';
    private const PARAMS_EXAMPLE = ['paramKey' => 'paramValue'];

    /** @var ProctorioAccessProvider|MockObject */
    private $proctorioProviderMock;

    /** @var ProctorioService */
    private $subject;

    /** @var ProctorioConfig|MockObject */
    private $configMock;


    protected function setUp(): void
    {
        $this->proctorioProviderMock = $this->createMock(ProctorioAccessProvider::class);
        $this->configMock = $this->createMock(ProctorioConfig::class);
        $this->subject = new ProctorioService(
            $this->proctorioProviderMock,
            $this->configMock
        );
    }

    public function testCallRemoteProctoring(): void
    {
        $this->configMock->expects($this->once())
            ->method('configure')
            ->with(self::PARAMS_EXAMPLE)
            ->willReturn(self::CONFIG_EXAMPLE);
        $this->proctorioProviderMock->expects($this->once())
            ->method('retrieve')
            ->with(self::CONFIG_EXAMPLE, self::SECRET)
            ->willReturn('string');
        $this->subject->callRemoteProctoring(self::PARAMS_EXAMPLE, self::SECRET);
    }
}
