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


class Normalizer
{
    public function normalize(array $params): string
    {
        return http_build_query($params);
    }
}
//public static string ToNormalizedString (this NameValueCollection collection, IList<string> excludedNames = null) {
//    List<KeyValuePair<string, string>> list = new List<KeyValuePair<string, string>> ();
//
//            foreach (string key in collection.AllKeys) {
//        if (excludedNames != null && excludedNames.Contains (key)) continue;
//
//        string value = collection[key] ?? string.Empty;
//                list.Add (new KeyValuePair<string, string> (HttpUtility.UrlDecode (key).ToRfc3986EncodedString (), HttpUtility.UrlDecode (value).ToRfc3986EncodedString ()));
//            }
//
//            StringBuilder normalizedString = new StringBuilder ();
//
//            foreach (KeyValuePair<string, string> pair in list) normalizedString.Append ('&').Append (pair.Key).Append ('=').Append (pair.Value);
//
//            return normalizedString.ToString ().TrimStart ('&');
//        }