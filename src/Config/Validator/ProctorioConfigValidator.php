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
    /** @var ValidatorInterface */
    private $examSettingsValidator;

    /** @var ValidatorInterface */
    private $examTagValidator;

    /** @var ValidatorInterface */
    private $examUrlValidator;

    /** @var ValidatorInterface */
    private $examTakeUrlValidator;

    /** @var ValidatorInterface */
    private $oauthConsumerKeyValidator;

    /** @var ValidatorInterface */
    private $oauthNonceValidator;

    /** @var ValidatorInterface */
    private $oauthSignatureMethodValidator;

    /** @var ValidatorInterface */
    private $oauthTimestampValidator;

    /** @var ValidatorInterface */
    private $oauthVersionValidator;

    /** @var ValidatorInterface */
    private $userFullNameValidator;

    /** @var ValidatorInterface */
    private $userIdValidator;

    public function __construct(
        ValidatorInterface $examSettingsValidator = null,
        ValidatorInterface $examTagValidator = null,
        ValidatorInterface $examUrlValidator = null,
        ValidatorInterface $examTakeUrlValidator = null,
        ValidatorInterface $oauthConsumerKeyValidator = null,
        ValidatorInterface $oauthNonceValidator = null,
        ValidatorInterface $oauthSignatureMethodValidator = null,
        ValidatorInterface $oauthTimestampValidator = null,
        ValidatorInterface $oauthVersionValidator = null,
        ValidatorInterface $userFullNameValidator = null,
        ValidatorInterface $userIdValidator = null
    ) {
        $this->examSettingsValidator = $examSettingsValidator ?? new ExamSettingsValidator();
        $this->examTagValidator = $examTagValidator ?? new ExamTagValidator();
        $this->examUrlValidator = $examUrlValidator ?? new ExamUrlValidator(500);
        $this->examTakeUrlValidator = $launchUrlValidator ?? new ExamUrlValidator(1000);
        $this->oauthConsumerKeyValidator = $oauthConsumerKeyValidator ?? new OauthConsumerKeyValidator();
        $this->oauthNonceValidator = $oauthNonceValidator ?? new OauthNonceValidator();
        $this->oauthSignatureMethodValidator = $oauthSignatureMethodValidator ?? new OauthSignatureMethodValidator();
        $this->oauthTimestampValidator = $oauthTimestampValidator ?? new OauthTimestampValidator();
        $this->oauthVersionValidator = $oauthVersionValidator ?? new OauthVersionValidator();
        $this->userFullNameValidator = $userFullNameValidator ?? new UserFullNameValidator();
        $this->userIdValidator = $userIdValidator ?? new UserIdValidator();
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        $validators = $this->getValidators();

        foreach ($value as $configName => $configValue) {
            /** @var ValidatorInterface $validator */
            $validator = $validators[$configName] ?? null;

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
                        '%s: %s',
                        $configName,
                        $exception->getMessage()
                    )
                );
            }
        }
    }

    private function getValidators(): array
    {
        return [
            ProctorioConfig::LAUNCH_URL => $this->examUrlValidator,
            ProctorioConfig::USER_ID => $this->userIdValidator,
            ProctorioConfig::OAUTH_CONSUMER_KEY => $this->oauthConsumerKeyValidator,
            ProctorioConfig::EXAM_START => $this->examUrlValidator,
            ProctorioConfig::EXAM_TAKE => $this->examTakeUrlValidator,
            ProctorioConfig::EXAM_END => $this->examUrlValidator,
            ProctorioConfig::EXAM_SETTINGS => $this->examSettingsValidator,
            ProctorioConfig::EXAM_TAG => $this->examTagValidator,
            ProctorioConfig::FULL_NAME => $this->userFullNameValidator,
            ProctorioConfig::OAUTH_SIGNATURE_METHOD => $this->oauthSignatureMethodValidator,
            ProctorioConfig::OAUTH_VERSION => $this->oauthVersionValidator,
            ProctorioConfig::OAUTH_TIMESTAMP => $this->oauthTimestampValidator,
            ProctorioConfig::OAUTH_NONCE => $this->oauthNonceValidator,
        ];
    }
}
