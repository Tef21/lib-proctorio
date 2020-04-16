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

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use oat\Proctorio\Exception\InvalidProctorioResponseException;
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
            'http://localhost',
            $this->clientMock
        );
    }

    public function testExecuteRequestSuccessfully(): void
    {
        $payload = '';

        $this->responseMock
            ->method('getBody')
            ->willReturn(
                json_encode(
                    [
                        'url1',
                        'url2',
                    ]
                )
            );

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
                    $payload
                )
            )
            ->willReturn($this->responseMock);

        $proctorioResponse = $this->subject->execute($payload);

        $this->assertSame('url1', $proctorioResponse->getTestTakerUrl());
        $this->assertSame('url2', $proctorioResponse->getTestReviewerUrl());
    }

    public function testExecuteRequestReturnsInvalidResponse(): void
    {
        $this->responseMock
            ->method('getBody')
            ->willReturn(
                json_encode(
                    [
                        'url1'
                    ]
                )
            );

        $this->clientMock
            ->expects($this->once())
            ->method('send')
            ->willReturn($this->responseMock);

        $this->expectException(InvalidProctorioResponseException::class);
        $this->expectExceptionMessage('Invalid Proctorio response');

        $this->subject->execute('');
    }

    public function testExecuteRequestThrowsGuzzleException(): void
    {
        $this->responseMock
            ->method('getBody')
            ->willReturn(
                json_encode(
                    [
                        'url1'
                    ]
                )
            );

        $this->clientMock
            ->expects($this->once())
            ->method('send')
            ->willThrowException($this->createMock(GuzzleException::class));

        $this->expectException(InvalidProctorioResponseException::class);
        $this->expectExceptionMessage('Invalid Proctorio response');

        $this->subject->execute('');
    }

    /**
     * @dataProvider invalidResponseCodeProvider
     */
    public function testExecuteRequestReturnsInvalidResponseCode(int $responseCode, string $expectedMessage): void
    {
        $responseData = json_encode([$responseCode, $expectedMessage]);

        $this->responseMock
            ->method('getBody')
            ->willReturn($responseData);

        $this->clientMock
            ->expects($this->once())
            ->method('send')
            ->willReturn($this->responseMock);

        try {
            $this->subject->execute('');
        } catch (InvalidProctorioResponseException $exception) {
            $this->assertTrue($exception instanceof InvalidProctorioResponseException);
            $this->assertSame($expectedMessage, $exception->getMessage());
            $this->assertSame($responseData, $exception->getResponseData());
        }
    }

    public function invalidResponseCodeProvider(): array
    {
        return [
            [
                2653,
                'Missing required parameters',
            ],
            [
                2654,
                'Invalid parameter',
            ],
            [
                2655,
                'Incorrect consumer key',
            ],
            [
                2656,
                'Signature is invalid'
            ],
            [
                2657,
                'The used timestamp is out of range'
            ],
            [
                2658,
                'Invalid exam tag ID'
            ],
            [
                2659,
                'Invalid settings'
            ],
            [
                2660,
                'Unknown'
            ],
        ];
    }
}
