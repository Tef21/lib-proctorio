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


namespace oat\Proctorio;

use Ramsey\Uuid\Uuid;

class ProctorioRequiredParameters
{
    private const DEFAULT = 'default';
    /** @var string */
    private $launchUrl;

    /** @var string */
    private $userId;

    /** @var string */
    private $oauthConsumerKey;

    /** @var string */
    private $examTake;

    /** @var string */
    private $examEnd;

    /** @var string */
    private $fullName;

    /** @var string */
    private $examTag;

    /** @var int */
    private $oauthTimestamp;

    /** @var string */
    private $oauthNonce;

    public function __construct(
        string $launchUrl,
        string $userId,
        string $oauthConsumerKey,
        string $examTake,
        string $examEnd,
        string $fullName = self::DEFAULT,
        string $examTag = self::DEFAULT,
        int $oauthTimestamp = null,
        string $oauthNonce = null
    )
    {
        $this->launchUrl = $launchUrl;
        $this->userId = $userId;
        $this->oauthConsumerKey = $oauthConsumerKey;
        $this->examTake = $examTake;
        $this->examEnd = $examEnd;
        $this->fullName = $fullName;
        $this->examTag = $examTag;
        $this->oauthTimestamp = $oauthTimestamp;
        $this->oauthNonce = $oauthNonce;


        if ($this->oauthTimestamp = null) {
            $this->oauthTimestamp = time();
        }

        if ($this->oauthNonce === null) {
            $this->oauthNonce = Uuid::uuid4();
        }
    }

    public function toArray(): array
    {
        return [
            ProctorioConfig::LAUNCH_URL => $this->launchUrl,
            ProctorioConfig::USER_ID => $this->userId,
            ProctorioConfig::OAUTH_CONSUMER_KEY => $this->oauthConsumerKey,
            ProctorioConfig::EXAM_TAKE => $this->examTake,
            ProctorioConfig::EXAM_END => $this->examEnd,
            ProctorioConfig::FULL_NAME => $this->fullName,
            ProctorioConfig::EXAM_TAG => $this->examTag,
            ProctorioConfig::OAUTH_TIMESTAMP => $this->oauthTimestamp,
            ProctorioConfig::OAUTH_NONCE => $this->oauthNonce,
            ProctorioConfig::EXAM_START => $this->launchUrl
        ];
    }
}
