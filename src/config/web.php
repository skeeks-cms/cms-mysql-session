<?php
return [
    'components' => [
        'session' => [
            'class' => \skeeks\cms\mysqlSession\DbSession::class,
        ],
    ],
];