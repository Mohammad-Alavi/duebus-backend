<?php
return [
    'supportsCredentials' => true,
    'maxAge'              => 3600,
    'allowedOrigins'      => ['*'],
    'allowedHeaders'      => ['*'],
    'allowedMethods'      => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    'exposedHeaders'      => [''],
];
