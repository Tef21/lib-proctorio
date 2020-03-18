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

class RequestBuilder
{

    public function buildRequest(string $payload)
    {
        // init the resource
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => ProctorioConfig::PROCTORIO_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        // execute
        $output = curl_exec($ch);


        if (curl_errno($ch)) {
            print curl_error($ch);
        }

        var_export($output);


        // free
        curl_close($ch);

        return $output;
    }

}