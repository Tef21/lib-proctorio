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

use oat\Proctorio\ProctorioAccessProvider;
use oat\Proctorio\ProctorioRequestHandler;
use oat\Proctorio\SignatureBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ProctorioProviderTest extends TestCase
{

    private const EXAMPLE_PAYLOAD = ['examplePayload' => 'WithSomeValues'];
    private const SECRET = 'secret';
    private const SIGNATURE_EXAMPLE = 'signature_example';
    private const RESPONSE_BODY_EXAMPLE = 'some response';


    /** @var ProctorioRequestHandler|MockObject */
    private $proctorioRequestHandlerMock;

    /** @var SignatureBuilder|MockObject */
    private $signatureBuilderMock;

    /** @var ProctorioAccessProvider */
    private $subject;

    /** @var ResponseInterface|MockObject */
    private $responseMock;

    protected function setUp(): void
    {
        $this->responseMock = $this->createMock(ResponseInterface::class);
        $this->proctorioRequestHandlerMock = $this->createMock(ProctorioRequestHandler::class);
        $this->signatureBuilderMock = $this->createMock(SignatureBuilder::class);
        $this->subject = new ProctorioAccessProvider(
            $this->proctorioRequestHandlerMock,
            $this->signatureBuilderMock
        );
    }

    public function testRetrieve(): void
    {
        $this->signatureBuilderMock->expects($this->once())
            ->method('buildSignature')
            ->willReturn(self::SIGNATURE_EXAMPLE);

        $this->proctorioRequestHandlerMock->expects($this->once())
            ->method('execute')
            ->with('examplePayload=WithSomeValues&oauth_signature=signature_example')
            ->willReturn($this->responseMock);

        $streamMock = $this->createMock(StreamInterface::class);
        $this->responseMock->expects($this->once())->method('getBody')->willReturn($streamMock);

        $this->subject->retrieve(self::EXAMPLE_PAYLOAD, self::SECRET);
    }
}
