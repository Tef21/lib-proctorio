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

namespace oat\Proctorio\tests\integration;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use oat\Proctorio\Encoder;
use oat\Proctorio\Normalizer;
use oat\Proctorio\ProctorioConfig;
use oat\Proctorio\ProctorioProvider;
use oat\Proctorio\ProctorioRequestHandler;
use oat\Proctorio\ProctorioService;
use oat\Proctorio\SignatureBuilder;
use PHPUnit\Framework\TestCase;

class ProctorioServiceTest extends TestCase
{
    /** @var Encoder */
    private $encoder;

    /** @var Normalizer */
    private $normalizer;

    /** @var SignatureBuilder */
    private $signatureBuilder;

    /** @var ProctorioRequestHandler */
    private $requestHandler;

    /** @var ProctorioProvider */
    private $provider;

    /** @var ProctorioConfig */
    private $config;

    /** @var ProctorioService */
    private $subject;

    /** @var HandlerStack */
    private $handler;

    /** @var MockHandler */
    private $mock;

    protected function setUp(): void
    {
        $this->mock = new MockHandler([
            new Response(200, [], 'Hello, World'),
        ]);

        $this->handler = HandlerStack::create($this->mock);
        $config['handler'] = $this->handler;

        $guzzleClient = new Client($config);
        $this->requestHandler = new ProctorioRequestHandler(
            $guzzleClient
        );
        $this->encoder = new Encoder();
        $this->normalizer = new Normalizer();
        $this->signatureBuilder = new SignatureBuilder(
            $this->encoder,
            $this->normalizer
        );
        $this->provider = new ProctorioProvider(
            $this->requestHandler,
            $this->signatureBuilder,

        );
        $this->config = new ProctorioConfig();

        $this->subject = new ProctorioService(
            $this->provider,
            $this->config
        );
    }

    public function testCallRemoteProctoring(): void
    {
        $result = $this->subject->callRemoteProctoring($this->subject->buildConfig([]), 'secret');
        $this->assertSame('Hello, World', $result);
        $lastRequest = $this->mock->getLastRequest();
        $this->assertSame('POST', $lastRequest->getMethod());
    }
}