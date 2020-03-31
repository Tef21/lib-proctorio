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
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{
    /** @var Encoder */
    private $subject;

    public function getUrlSources(): array
    {
        return [
            [
                'http://getproctorio.com/',
                'http%3A%2F%2Fgetproctorio.com%2F',
            ],
            [
                'http://getproctorio.com/?d603645bf7d642a6bcc240f98694c4c9%2CUYKTLaemy21ytqiXHQXDmw%3D%3D%2CFIFamBF89WKWoU0jrLNRvAeiTpqR19qqRBblHB59sK7ppKUY44K9rIGx9Lbkxzz9caDAsa6AknGZywVJlxaOUvVufXyxtlsGrdrymFKB2vVcGI4OJ%2BfU4PQMdNpG9RFJzrDEEIYkvPuW41XV2WDUbgt7WO%2FibfNc0UfN4xO44fS%2F3AZx5BxFe%2F967Rrvxcb9FRIgHQuQUaydCOIkK1B%2Fg1hF0gQUeQtplZjNfJDCJmSHml8VyPo1PiZLEHo6N9RGCnKjCE9G6v5Wol6nzvaSpsc02YhsfYfm1rNyPLIro2Okx58JPUd9wWRP8G3YLIFjL6uGzj7aaZQQyoaLip%2FBGv1s6m2U4FOiITSRbRR21TM3d5BBgLDq2DAy4fycygDCL7CFGe2pT%2FDuvnVBbVI8%2F72HqQUB08Xx2dkgvPCqmyKMBotWmGhG1wELLK8YA%2BzpRCY1n2E5NZX1meMjzUo2e9kZjf18ERjOSFV1%2Fs7L2qNOhPWDy2BFHOMSc%2B6PUquTtCTWv%2Fxli1MJBfHW7xeB90gB5NttFTEfa%2BlD927eScRODKU96q7AKLYxLBuhE6G4AcUvnsk3rZwyy4cknQqWHsMddHO7Dl6C2mi7VAYIEKJl%2F7DJVoqE7S9unfx2by%2FKfCZd%2B32FiqVouYl9kun0JF5o3gJFYtBz8RgZ02JfoWhulUjEHBDmsJ%2Fp2VI12kkPFLF8FziC%2FGZDtL%2BKCdgEcR5GFMgXHKx15i4bBWaxNajnl5KTWugGwoP72mWKkgkZZ6xpll0ZpZdBDyJsGlxekh4ORhJjOfFURBQ27JVdT399DaCWInpCGlexDztbr8XfFHIS8Xsgxp8CaMb3ZvT7MG%2B2MM9Ybibb1dTybDahEtWLMZcCOhXtJ4EjkyqnLgecie10X26UDRnzgq3NZJoG9%2B59ZqrQ%2Fot1Eej6CMliG1l%2FEuufnKN2skctwdVFWjX0aj4Si8kKQ2Bt5x213Law7Y%2Fs4tjKBrXwu6Dv7lnK8i4%3D#tag',
                'http%3A%2F%2Fgetproctorio.com%2F%3Fd603645bf7d642a6bcc240f98694c4c9%252CUYKTLaemy21ytqiXHQXDmw%253D%253D%252CFIFamBF89WKWoU0jrLNRvAeiTpqR19qqRBblHB59sK7ppKUY44K9rIGx9Lbkxzz9caDAsa6AknGZywVJlxaOUvVufXyxtlsGrdrymFKB2vVcGI4OJ%252BfU4PQMdNpG9RFJzrDEEIYkvPuW41XV2WDUbgt7WO%252FibfNc0UfN4xO44fS%252F3AZx5BxFe%252F967Rrvxcb9FRIgHQuQUaydCOIkK1B%252Fg1hF0gQUeQtplZjNfJDCJmSHml8VyPo1PiZLEHo6N9RGCnKjCE9G6v5Wol6nzvaSpsc02YhsfYfm1rNyPLIro2Okx58JPUd9wWRP8G3YLIFjL6uGzj7aaZQQyoaLip%252FBGv1s6m2U4FOiITSRbRR21TM3d5BBgLDq2DAy4fycygDCL7CFGe2pT%252FDuvnVBbVI8%252F72HqQUB08Xx2dkgvPCqmyKMBotWmGhG1wELLK8YA%252BzpRCY1n2E5NZX1meMjzUo2e9kZjf18ERjOSFV1%252Fs7L2qNOhPWDy2BFHOMSc%252B6PUquTtCTWv%252Fxli1MJBfHW7xeB90gB5NttFTEfa%252BlD927eScRODKU96q7AKLYxLBuhE6G4AcUvnsk3rZwyy4cknQqWHsMddHO7Dl6C2mi7VAYIEKJl%252F7DJVoqE7S9unfx2by%252FKfCZd%252B32FiqVouYl9kun0JF5o3gJFYtBz8RgZ02JfoWhulUjEHBDmsJ%252Fp2VI12kkPFLF8FziC%252FGZDtL%252BKCdgEcR5GFMgXHKx15i4bBWaxNajnl5KTWugGwoP72mWKkgkZZ6xpll0ZpZdBDyJsGlxekh4ORhJjOfFURBQ27JVdT399DaCWInpCGlexDztbr8XfFHIS8Xsgxp8CaMb3ZvT7MG%252B2MM9Ybibb1dTybDahEtWLMZcCOhXtJ4EjkyqnLgecie10X26UDRnzgq3NZJoG9%252B59ZqrQ%252Fot1Eej6CMliG1l%252FEuufnKN2skctwdVFWjX0aj4Si8kKQ2Bt5x213Law7Y%252Fs4tjKBrXwu6Dv7lnK8i4%253D%23tag'
            ]
        ];
    }

    protected function setUp(): void
    {
        $this->subject = new Encoder();
    }

    /**
     * @dataProvider getUrlSources
     */
    public function testEncode(string $stringToEncode, string $expected): void
    {
        $result = $this->subject->encode($stringToEncode);
        $this->assertEquals($expected, $result);
    }
}
