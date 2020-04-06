# RemoteProctoringService

Proctorio service allow to communicate with Proctorio api. 

## Implementation
ProctorioService is an implementation of RemoteProctoringServiceInterface that allow:
- calling remote proctoring
- buillding a config for remote proctoring call


## Example
To use library you can use `ProctorioService` class

```php
<?php declare(strict_types=1);

use oat\Proctorio\ProctorioService;

class MyProctorioHandler
{
    public function getProctorioService()
    {
        return new ProctorioService();
    }
    
    public function handleProctoring()
    {
        $config = $this->getProctorioService()->buildConfig([]);
        $this->getProctorioService()->callRemoteProctoring($config, 'secret');
    }
}


```
