<?php

return [
    'class' => 'yii\db\Connection',
    //'mysql:host=localhost;dbname=deb-track-innovations'
    //'mysql:host=dtimysql;dbname=deb-track-innovations'
    'dsn' => 'mysql:host=localhost;dbname=deb-track-innovations',
    'username' => 'root', // root || user
    'password' => '', // '' || password
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
