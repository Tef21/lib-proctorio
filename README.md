# Proctorio
Proctorio POC



### Endpoints & Methods  
Follow these key points about passing data correctly to Proctorio:  
- For the launch API, post all data to:  https://{account region}5499ws.proctor.io/6521ca945bd84cfc85d2767da06aa7c8  
- The service supports only data submitted HTTP POST, akin to method=post in HTML  forms or -data parameter if using cURL.  
- The service only handles one launch URL at a time through each POST.  
- The launch URLs must be 100% self-contained, meaning being fully ready to take the user  to an authenticated session without additional authentication.  
- HTTPS must be used when generating the URLs  
- Only TLS 1.2 and 1.3 are supported. 


### Authorization
The string consists of the HTTP method (in uppercase), base URL, and Parameters in a single  string.  
- Convert the HTTP method to uppercase  
- Append an “&” character  
- Percent encode the absolute URL and append  
- Append an “&” character  
- Percent encode the parameter string and append   

Note: Oauth should not be assumed

### Parameters
```
ProctorioConfig::LAUNCH_URL,
ProctorioConfig::OAUTH_CONSUMER_KEY,
ProctorioConfig::EXAM_START,
ProctorioConfig::USER_ID,
ProctorioConfig::EXAM_TAKE,
ProctorioConfig::EXAM_END,
ProctorioConfig::EXAM_SETTINGS,
ProctorioConfig::FULL_NAME,
ProctorioConfig::EXAM_TAG,
ProctorioConfig::OAUTH_SIGNATURE_METHOD,
ProctorioConfig::OAUTH_VERSION,
ProctorioConfig::OAUTH_TIMESTAMP,
ProctorioConfig::OAUTH_NONCE,
````


Request Sample  The request sample below is written in C# (sharp):    

The request sample below is written in C# (sharp):    
```
        NameValueCollection parameters = new NameValueCollection()             {
                 { "launch_url", "https://proctorio.com/customers" },
                 { "user_id", "1de2d4870b5f409098f8df8fc5186e7d" },
                 { "oauth_consumer_key", "f6f12656e1204a9b9f3eb06bc786cbea" },
                 { "exam_start", "https://proctorio.com/customers" },
                 { "exam_take", "https://proctorio.com/about" },
                 { "exam_end", "https://proctorio.com/platform" },
                 { "exam_settings", "webtraffic,recordvideo" },
                 { "fullname", "John Doe" },
                 { "exam_tag", "Quiz 1" },
                 { "oauth_signature_method", "HMAC-SHA1" },
                 { "oauth_version", "1.0" },
                 { "oauth_timestamp", ((DateTime.UtcNow.Ticks - 621355968000000000) /  10000000).ToString() },
                 { "oauth_nonce", "​ Guid.NewGuid()​ " }             
        }; 
```

### Error Codes 

1. [2653] = Missing required parameters 
2. [2654] = Invalid parameter 
3. [2655] = Incorrect consumer key 
4. [2656] = Signature is invalid 
5. [2657] = The used timestamp is out of range 
6. [2658] = Invalid exam tag ID 
7. [2659] = Invalid settings 