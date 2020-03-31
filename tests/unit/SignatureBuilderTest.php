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

namespace oat\Proctorio\tests\unit;

use oat\Proctorio\Encoder;
use oat\Proctorio\Normalizer;
use oat\Proctorio\SignatureBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SignatureBuilderTest extends TestCase
{
    /** @var SignatureBuilder */
    private $subject;

    /** @var Encoder|MockObject */
    private $encoderMock;

    /** @var Normalizer|MockObject */
    private $normalizerMock;

    protected function setUp(): void
    {
        $this->subject = new SignatureBuilder();
        $this->encoderMock = $this->createMock(Encoder::class);
        $this->normalizerMock = $this->createMock(Normalizer::class);
    }

    public function testBuildSignature()
    {
        $this->encoderMock->method('encode')->willReturn('key=value');
        $result = $this->subject->buildSignature($this->encoderMock, $this->normalizerMock, ['payload'], 'secret');

        $this->assertEquals('B8HpmpY7D3isaP19rbwPchlVkNk=', $result);
    }
}