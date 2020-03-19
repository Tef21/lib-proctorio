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

namespace Proctorio;

class ProctorioConfig
{

    // https://{account region}5499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8
    public const PROCTORIO_URL = 'https://us15499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8';
//    public const PROCTORIO_URL = 'https://{areaofexams}15499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8';
//    public const PROCTORIO_URL = 'https://premium.docker.localhost/proctorio/test.php';

    //HTTPS must be used when generating the URLs
    //Only TLS 1.2 and 1.3 are supported.
    //The signature base string is used to generate the request signing key.
    // Proctorio uses percent encoding based strictly on RFC3986.
    //mandatory

    public const LAUNCH_URL = 'launch_url';
    //Must contain a valid absolute URL, fully
    //launches to the exam start page with no
    //additional authentication. The launch_url
    //value is the same as the exam_start
    //value.
    //max length = 500
    //mandatory

    public const USER_ID = 'user_id';
    //alphanumeric (hyphens    //also acceptable)
    //max length = 36
    //mandatory

    public const OAUTH_CONSUMER_KEY = 'oauth_consumer_key';
    // Provided by a Proctorio representative
    // max length = 32
    //mandatory

    public const EXAM_START = 'exam_start';
    //Regular expression to match the exam  start page.
    // Any pages prior to this will be  considered pre-exam pages and will be  ignored.
    // The launch_url value is the same  as the exam_start value.
    // This is the URL  that the Test Taker is on before they  begin the assessment.
    // max length = 500
    //mandatory

    public const EXAM_TAKE = 'exam_take';
    //Regular expression to match the in-exam  page URLs (the URL of the assessment). 
    // In cases where there are questions on  multiple pages, this is important. 
    // Anything else visited that does not match  this or the exam_end parameter will be 
    // considered leaving the exam and the session will be considered complete
    // max length = 1000
    //mandatory

    public const EXAM_END = 'exam_end';
    //Regular expression to match the exam  end page
    // (the URL the test taker is taken  to once the assessment has been  completed).
    // This triggers the proctoring  session to end and assumes that it has  been submitted correctly.
    // Anything else  visited that does not match this or the  exam_take parameter will be considered 
    // leaving the exam and the session will be  considered complete.  
    // max length = 500
    //mandatory

    public const EXAM_SETTINGS = 'exam_settings';
    // Exam Setting  Description 
    //recordvideo  Requires the Test Taker to have a webcam and will record the  webcam for the entire duration of the exam 
    //recordaudio  Requires the Test Taker to have a microphone and will record audio  for the entire exam duration of the exam 
    //recordscreen  Will capture and record the full desktop screen for the entire duration  of the exam 
    //recordwebtraffic  Will capture screenshots and URLs of any websites visited during the  exam 
    //recordroomstart   
    //Requires the Test Taker to perform a room scan at the start of the  exam 
    //verifyvideo  Will ensure that the webcam is working and it is not virtualized or  broken 
    //verifyaudio  Will ensure that the microphone is working and it is not virtualized or  muted 
    //verifydesktop  Will ensure that the desktop recording is working and is being  properly recorded 
    //verifyidauto      ---or---    *  verifyidlive   
    //Requires the Test Taker to present photo identification prior to  starting the exam and will be automatically scanned        Requires the Test Taker to present photo identification prior to  starting the exam. The ID is then reviewed and verified by a Proctorio  agent before they are allowed into the exam 
    //verifysignature  Requires the Test Taker to sign an agreement before exam start 
    //      fullscreenlenient    ---or---    fullscreenmoderate    ---or---    fullscreensevere   
    //Forces the exam in fullscreen, preventing access to other applications  and websites    Navigating away from the exam page for more than 30 seconds  results in removal from the exam      Navigating away from the exam page for more than 15 seconds results  in removal from the exam      Navigating away from the exam page results in instant removal from  the exam 
    //clipboard  Disables copy/paste functionality, including screen printing and the  context menu 
    //notabs    ---or---    linksonly 
    //Disables new tabs or windows during the exam        Disables new tabs or windows during the exam except links  embedded in the exam page 
    //closetabs  Forces all other tabs and windows to be closed before the exam starts 
    //onescreen  Forces the Test Taker to disable all but one monitor before starting  the exam 
    //print  Disables printing exam content to prevent exam distribution 
    //downloads  Prevents the Test Taker from downloading files through the browser 
    //cache  Empties system temporary files after the exam is submitted 
    //mandatory

    public const FULL_NAME = 'fullname';
    //Used in the exam agreement page which  is generated on start.
    // This information is  never stored and is optional 
    // max length = 100
    public const EXAM_TAG = 'exam_tag';
    //This is the exam ID tag and will be added  to the end of the launch and review URLs. 
    // When provided, it prevents it from being 
    // manipulated by the user as it is more  secure. If it is sent,
    // then Proctorio factors  it into the response
    // max length = 100

    public const OAUTH_SIGNATURE_METHOD = 'oauth_signature_method';
    // HMAC-SHA1
    // mandatory

    public const OAUTH_VERSION = 'oauth_version';
    // 1.0 
    // mandatory

    public const OAUTH_TIMESTAMP = 'oauth_timestamp';
    //Epoch timestamp. Used to prevent  delayed attacks.
    // Must be within 8 minutes  of the correct time, otherwise, it is  rejected. 
    //mandatory

    public const OAUTH_NONCE = 'oauth_nonce';
    // Anything unique. Used to prevent replay  attacks 
    // mandatory

    public const HMAC_SHA_1 = 'HMAC-SHA1';
    //default value for OAUTH_SIGNATURE_METHOD

    public const DEFAULT_OAUTH_VERSION = '1.0';

    //default value for OAUTH_VERSION


    public function configure(array $parameters): array
    {
        return [
            self::LAUNCH_URL => $this->getDefaultValue($parameters, self::LAUNCH_URL, self::PROCTORIO_URL),
            self::USER_ID => $this->getDefaultValue($parameters, self::USER_ID),
            self::OAUTH_CONSUMER_KEY => $this->getDefaultValue($parameters, self::OAUTH_CONSUMER_KEY),
            self::EXAM_START => $this->getDefaultValue($parameters, self::EXAM_START),
            self::EXAM_TAKE => $this->getDefaultValue($parameters, self::EXAM_TAKE),
            self::EXAM_END => $this->getDefaultValue($parameters, self::EXAM_END),
            self::EXAM_SETTINGS => $this->getDefaultValue($parameters, self::EXAM_SETTINGS),
            self::FULL_NAME => $this->getDefaultValue($parameters, self::FULL_NAME),
            self::EXAM_TAG => $this->getDefaultValue($parameters, self::EXAM_TAG),

            self::OAUTH_SIGNATURE_METHOD => self::HMAC_SHA_1,
            self::OAUTH_VERSION => $this->getDefaultValue($parameters, self::OAUTH_VERSION, self::DEFAULT_OAUTH_VERSION),
            self::OAUTH_TIMESTAMP => $this->getDefaultValue($parameters, self::OAUTH_TIMESTAMP),
            self::OAUTH_NONCE => $this->getDefaultValue($parameters, self::OAUTH_NONCE),
        ];
    }

    private function getDefaultValue(array $parameters, $field, $default = '')
    {
        return $parameters[$field] ?? $default;
    }
}
