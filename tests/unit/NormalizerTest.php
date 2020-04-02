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

use oat\Proctorio\Normalizer;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{

    /** @var Normalizer */
    private $subject;

    public function getResolvedParameters(): array
    {
        return [
            [
                ['key' => 'value'], 'key=value'
            ],
            [
                [
                    '$0me Specia|' => ';][=-+_)(*&&^%$#@!£~<>?',
                    'multiple' => 'elements'
                ], '%240me%20Specia%7C=%3B%5D%5B%3D-%20_%29%28%2A%26%26%5E%25%24%23%40%21%C2%A3~%3C%3E%3F&multiple=elements'
            ]
        ];
    }

    protected function setUp(): void
    {
        $this->subject = new Normalizer();
    }

    /**
     * @dataProvider getResolvedParameters
     */
    public function testNormalize(array $params, string $expectations): void
    {
        $result = $this->subject->normalize($params);
        $this->assertEquals($expectations, $result);
    }
}
