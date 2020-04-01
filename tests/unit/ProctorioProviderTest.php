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
 * Time: 15:37
 */

namespace oat\Proctorio\tests\unit;

use oat\Proctorio\ProctorioProvider;
use oat\Proctorio\ProctorioRequestHandler;
use oat\Proctorio\SignatureBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

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

    /** @var ProctorioProvider */
    private $subject;

    /** @var ResponseInterface|MockObject */
    private $responseMock;

    protected function setUp(): void
    {
        $this->responseMock = $this->createMock(ResponseInterface::class);
        $this->proctorioRequestHandlerMock = $this->createMock(ProctorioRequestHandler::class);
        $this->signatureBuilderMock = $this->createMock(SignatureBuilder::class);
        $this->subject = new ProctorioProvider(
            $this->proctorioRequestHandlerMock,
            $this->signatureBuilderMock
        );
    }

    public function testRetrieve()
    {
        $this->signatureBuilderMock->expects($this->once())
            ->method('buildSignature')
            ->willReturn(self::SIGNATURE_EXAMPLE);

        $this->proctorioRequestHandlerMock->expects($this->once())
            ->method('execute')
            ->with('examplePayload=WithSomeValues&oauth_signature=signature_example')
            ->willReturn($this->responseMock);

        $this->responseMock->expects($this->once())->method('getBody')->willReturn(self::RESPONSE_BODY_EXAMPLE);

        $this->subject->retrieve(self::EXAMPLE_PAYLOAD, self::SECRET);
    }
}
