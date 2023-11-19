<?php

declare(strict_types=1);

return [
    // base route
    'GET <controller>' => '<controller>/index',
    'GET <controller>/<id:\d+>' => '<controller>/view',
    'GET <controller>/<uuid:[\w-]{36}>' => '<controller>/view',
    'POST <controller>' => '<controller>/create',
    'POST,PUT,PATCH <controller>/<id:\d+>' => '<controller>/update',
    'POST,PUT,PATCH <controller>/<uuid:[\w-]{36}+>' => '<controller>/update',
    'DELETE <controller>/<id:\d+>' => '<controller>/delete',
    'DELETE <controller>/<uuid:[\w-]{36}>' => '<controller>/delete',
    'GET,POST <controller>/<action>' => '<controller>/<action>',
];
