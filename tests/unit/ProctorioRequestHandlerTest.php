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

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use oat\Proctorio\ProctorioRequestHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ProctorioRequestHandlerTest extends TestCase
{
    /** @var ProctorioRequestHandler */
    private $subject;

    /** @var ClientInterface|MockObject */
    private $clientMock;

    /** @var ResponseInterface|MockObject */
    private $responseMock;

    protected function setUp(): void
    {
        $this->responseMock = $this->createMock(ResponseInterface::class);
        $this->clientMock = $this->createMock(ClientInterface::class);
        $this->subject = new ProctorioRequestHandler(
            $this->clientMock,
            'http://localhost'
        );
    }

    public function testBuildRequest(): void
    {
        $payload = '';
        $this->clientMock
            ->expects($this->once())
            ->method('send')
            ->with(
                new Request(
                    'POST',
                    'http://localhost',
                    [
                        'headers' => [
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ],
                        'curl' => [
                            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                        ],
                    ],
                    ''
                )
            )
            ->willReturn($this->responseMock);

        $this->subject->execute($payload);
    }
}
