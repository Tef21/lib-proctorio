# Proctorio config
Proctoprio configuration is included in `lib-proctorio/src/ProctorioConfig.php`


## Config static values

### `PROCTORIO_URL` - option
`https://{account region}5499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8`

HTTPS must be used when generating the URLs

Only TLS 1.2 and 1.3 are supported.

The signature base string is used to generate the request signing key.

Proctorio uses percent encoding based strictly on RFC3986.

### `CURRENT_DEFAULT_REGION` - option

### `LAUNCH_URL` - option
**mandatory**

max length = 500

### `USER_ID` - option
**mandatory**

alphanumeric (hyphens    //also acceptable)

max length = 36

### `OAUTH_CONSUMER_KEY` - option
**mandatory**

// max length = 32

### `EXAM_START` - option
**mandatory**

max length = 500

### `EXAM_TAKE` - option
**mandatory**

max length = 1000

### `EXAM_END` - option
**mandatory**

max length = 500

### `EXAM_SETTINGS` - option
**mandatory**


### `FULL_NAME` - option
max length = 100

### `EXAM_TAG` - option

This is the exam ID tag and will be added  to the end of the launch and review URLs. 

When provided, it prevents it from being manipulated by the user as it is more  secure. If it is sent, 
then Proctorio factors  it into the response

max length = 100

### `OAUTH_SIGNATURE_METHOD` - option
**mandatory**

HMAC-SHA1


### `OAUTH_VERSION` - option
**mandatory**

1.0

### `OAUTH_TIMESTAMP` - option
**mandatory**

Epoch timestamp. Used to prevent  delayed attacks.
Must be within 8 minutes  of the correct time, otherwise, it is rejected. 

### `OAUTH_NONCE` - option
**mandatory**

Anything unique. Used to prevent replay  attacks 

### `HMAC_SHA_1` - option
**mandatory**

default value for OAUTH_SIGNATURE_METHOD


### `DEFAULT_OAUTH_VERSION` - option

default value for OAUTH_VERSION

### `POST_MANHOOD` - option