# TOSBundle

[![In Progress](https://img.shields.io/badge/in%20progress-yes-red)](https://img.shields.io/badge/in%20progress-yes-red)

todo

## Documentation

todo

## Installation

todo

## Configuration

### Doctrine

You can define the `User` class to be used as relationship with TermsOfSericeSignature entity in `config/package/mp3000mp_tos.yaml`
```
mp3000mp_tos:
  doctrine:
    user:
      resolve_to: App\Entity\User
```

### Routes

You can choose the route prefix in `config/routes/mp3000mp_tos.yaml`
```
mp3000mp_tos:
  resource: "@Mp3000mpTOSBundle/Resources/config/routes.yaml"
  prefix: /tos
```

### Templates

You can override all templates located in `vendor/mp3000mp/TOSBundle/Resources/views/` in your own project directory `src/templates/bundles/Mp3000mpTOSBundle/`

## License

This bundle is under the Apache 2.0 license. See the complete license [in the bundle](LICENSE)

## About

UserBundle is a [mp3000mp](https://github.com/mp3000mp) initiative.
