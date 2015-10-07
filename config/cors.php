<?php
/*header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token');
header('Access-Control-Expose-Headers: Authorization');*/
return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value, the allowed methods however have to be explicitly listed.
     |
     */
    'defaults' => [
        'supportsCredentials' => false,
        'allowedOrigins' => array('*'),
        'allowedHeaders' => array('authorization','x-requested-with','apiKey', 'Origin', 'Content-Type', 'accept'),
        'allowedMethods' => array('POST', 'PUT', 'GET', 'DELETE'),
        'exposedHeaders' => array(),
        'maxAge' => 0,
        'hosts' => array(),
    ],

    'paths' => [
        'api/*' => array(
            'allowedOrigins' => array('*'),
            'allowedHeaders' => array('*'),
            'allowedMethods' => array('*'),
            'maxAge' => 3600*100,
        ),
        '*' => array(
            'allowedOrigins' => array('*'),
            'allowedHeaders' => array('Content-Type'),
            'allowedMethods' => array('POST', 'PUT', 'GET', 'DELETE'),
            'maxAge' => 3600,
            'hosts' => array('api.*'),
        ),
    ],
];

