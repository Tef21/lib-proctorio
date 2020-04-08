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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */

declare(strict_types=1);

namespace oat\Proctorio;

class SignatureBuilder
{
    /** @var Encoder */
    private $encoder;

    /** @var Normalizer */
    private $normalizer;

    public function __construct(Encoder $encoder = null, Normalizer $normalizer = null)
    {
        $this->encoder = $encoder;
        $this->normalizer = $normalizer;

        if ($this->encoder === null) {
            $this->encoder = new Encoder();
        }
        if ($this->normalizer === null) {
            $this->normalizer = new Normalizer();
        }
    }


    public function buildSignature(array $payload, string $secret): string
    {
        $signatureBaseString = $this->buildSignatureBaseString($payload);

        $computedSignature = hash_hmac('sha1', $signatureBaseString, $secret, true);
        return base64_encode($computedSignature);
    }

    private function buildSignatureBaseString(array $payload): string
    {
        return ProctorioConfig::POST_MANHOOD . '&'
            . $this->encoder->encode($payload[ProctorioConfig::LAUNCH_URL])
            . '&' . $this->encoder->encode($this->normalizer->normalize($payload));
    }
}
