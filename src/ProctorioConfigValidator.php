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


use oat\Proctorio\Exception\ProctorioParameterException;

class ProctorioConfigValidator
{
    /**
     * Check if all require parameters were set
     *
     * @throws ProctorioParameterException
     */
    public function validate(array $parameters): void
    {
        foreach ($this->requriedFields() as $field) {
            if (!isset($parameters[$field])) {
                throw new ProctorioParameterException(sprintf('Missing required key: %s', $field));
            }
        }
    }

    private function requriedFields(): array
    {
        return [
            ProctorioConfig::LAUNCH_URL,
            ProctorioConfig::USER_ID,
            ProctorioConfig::OAUTH_CONSUMER_KEY,
            ProctorioConfig::EXAM_TAKE,
            ProctorioConfig::EXAM_END,
            ProctorioConfig::EXAM_SETTINGS
        ];
    }

}
