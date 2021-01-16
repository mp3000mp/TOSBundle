# TOSBundle

Simple bundle that will help you forcing users to agree with terms of service before being able to use your application.

![Packagist Version](https://img.shields.io/packagist/v/mp3000mp/tos-bundle?color=%230273b3)
[![Build Status](https://travis-ci.org/mp3000mp/TOSBundle.svg?branch=master)](https://travis-ci.org/mp3000mp/TOSBundle)
[![Coverage Status](https://coveralls.io/repos/github/mp3000mp/TOSBundle/badge.svg?branch=master)](https://coveralls.io/github/mp3000mp/TOSBundle?branch=master)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)

 Table of Contents
 -----------------
 
  - [Documentation](#Documentation)
  - [Installation](#Installation)
  - [Configuration](#Configuration)
  - [Licence](#Licence)
  - [About](#About)

## Documentation

todo

## Installation

```sh
composer require mp3000mp/tos-bundle
```

## Configuration

### Doctrine

You can define the `User` class to be used as relationship with TermsOfSericeSignature entity in `config/package/mp3000mp_tos.yaml`
```
mp3000mp_tos:
  user_provider: App\Entity\User
```

### Routes

You can choose the route prefix in `config/routes/mp3000mp_tos.yaml`
```
mp3000mp_tos:
  resource: "@Mp3000mpTOSBundle/Resources/config/routes.yaml"
  prefix: /tos
```

### Templates

You can override all templates located in `vendor/Mp3000mp/TOSBundle/Resources/views/` in your own project directory `src/templates/bundles/Mp3000mpTOSBundle/`

## License

This bundle is under the Apache 2.0 license. See the complete license [in the bundle](LICENSE)

## About

TOSBundle is a [mp3000mp](https://github.com/mp3000mp) initiative.
