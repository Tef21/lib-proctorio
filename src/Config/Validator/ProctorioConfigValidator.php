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
use oat\Proctorio\ProctorioConfig;

class ProctorioConfigValidator implements ValidatorInterface
{
    /** @var ValidatorInterface[] */
    private $validators;

    public function __construct(ValidatorInterface ...$validators)
    {
        if (empty($validators)) {
            $validators = [
                ProctorioConfig::LAUNCH_URL => new ExamUrlValidator(true, 500),
                ProctorioConfig::USER_ID => new StringValidator(true, 36),
                ProctorioConfig::OAUTH_CONSUMER_KEY => new StringValidator(true, 32),
                ProctorioConfig::EXAM_START => new ExamUrlValidator(true, 500),
                ProctorioConfig::EXAM_TAKE => new ExamUrlValidator(true, 1000),
                ProctorioConfig::EXAM_END => new ExamUrlValidator(true, 500),
                ProctorioConfig::EXAM_SETTINGS => new ExamSettingsValidator(),
                ProctorioConfig::EXAM_TAG => new StringValidator(false, 100),
                ProctorioConfig::FULL_NAME => new StringValidator(false, 100),
                ProctorioConfig::OAUTH_SIGNATURE_METHOD => new OauthSignatureMethodValidator(),
                ProctorioConfig::OAUTH_VERSION => new OauthVersionValidator(),
                ProctorioConfig::OAUTH_TIMESTAMP => new OauthTimestampValidator(),
                ProctorioConfig::OAUTH_NONCE => new StringValidator(true),
            ];
        }

        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        foreach ($value as $configName => $configValue) {
            /** @var ValidatorInterface $validator */
            $validator = $this->validators[$configName] ?? null;

            if ($validator === null) {
                throw new ProctorioParameterException(
                    sprintf('There is no validator for config [%s]', $configName)
                );
            }

            try {
                $validator->validate($configValue);
            } catch (ProctorioParameterException $exception) {
                throw new ProctorioParameterException(
                    sprintf(
                        'Invalid [%s]: %s',
                        $configName,
                        $exception->getMessage()
                    )
                );
            }
        }
    }
}
