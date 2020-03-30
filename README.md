# lib-proctorio

![TAO Logo](https://github.com/oat-sa/taohub-developer-guide/raw/master/resources/tao-logo.png)



> Short description of `oat-sa/lib-proctorio`
>Proctorio ensures the total learning integrity of every assessment, every time

Long description of `oat-sa/lib-proctorio`
[Proctorio](https://proctorio.com/)

Using state-of-the-art technology and end-to-end data security, 
Proctorio ensures the total learning integrity of every assessment, every time. 
Our software eliminates human error, bias, and much of the expense associated with remote proctoring and identity verification.

Proctorio also integrates effortlessly with whatever learning management system you already use— no scheduling, waiting or additional logins required— for instant, objective, secure results.

## Installation instructions

These instructions assume that you have already a TAO installation on your system. If you don't, go to
[package/tao](https://github.com/oat-sa/package-tao) and follow the installation instructions over there.

Add the library to your TAO composer and to the autoloader:

Note that `oat-sa/lib-proctorio` is not registered on [Packagist](https://packagist.org/) so that you will need to add
a reference to your _composer.json_ before you can use `composer require`.
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:oat-sa/lib-proctorio.git"
    }
],
```
Now you can add it to `composer.json`:
```bash
composer require oat-sa/lib-proctorio
```
## Library Wiki
