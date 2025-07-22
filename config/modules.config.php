<?php

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Laminas\Mvc\I18n',
    'Laminas\I18n',
    'Laminas\Db',
    'Laminas\Cache',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\InputFilter',
    'Laminas\Filter',
    'Laminas\Paginator',
    'Laminas\Router',
    'Laminas\Validator',
    'Laminas\Cache\Storage\Adapter\Filesystem',
    'Laminas\Cache\Storage\Adapter\Memory',
    'DoctrineModule',
    'DoctrineORMModule',
    'Laminas\ApiTools\Versioning',
    'Laminas\ZendFrameworkBridge',
    'Laminas\ApiTools\ApiProblem',
    'Laminas\ApiTools\ContentNegotiation',
    'Laminas\ApiTools\Rpc',
    'Laminas\ApiTools\MvcAuth',
    'Laminas\ApiTools\Hal',
    'Laminas\ApiTools\Rest',
    'Laminas\ApiTools\ContentValidation',
    'Laminas\ApiTools',
    'Application',
];
