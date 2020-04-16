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

namespace oat\Proctorio\Config\Validator;

use oat\Proctorio\Exception\ProctorioParameterException;

class StringValidator implements ValidatorInterface
{
    /** @var int */
    private $maxLength;

    /** @var bool */
    private $required;

    public function __construct(bool $required = false, int $maxLength = 0)
    {
        $this->maxLength = $maxLength;
        $this->required = $required;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        if ($this->required && empty($value)) {
            throw new ProctorioParameterException('Mandatory parameter');
        }

        if ($value !== null && !is_string($value)) {
            throw new ProctorioParameterException('Parameter must be a string');
        }

        if (!empty($value) && $this->maxLength > 0 && strlen($value) > $this->maxLength) {
            throw new ProctorioParameterException(
                sprintf(
                    'Parameter with max length of %s characters',
                    $this->maxLength
                )
            );
        }
    }
}
