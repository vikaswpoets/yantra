<?php
return [
    'name'      => 'Yantra',
    'theme'     => 'Kaveri',
    'plugins'   => ['yantraadmin'],
    'language'  => 'en',
    'timezone'  => 'UTC',
    'charset'   => 'UTF-8',
    'proxyIPs'  => [],
    'email'     => [
        'userAgent'=>'Yantra',
        'protocol'=>'mail',
        'mailPath'=>'/usr/sbin/sendmail',
        'SMTPUser'=>'',
        'SMTPPass'=>'',
        'SMTPPort'=>25,
        'SMTPTimeout'=>5,
        'SMTPKeepAlive '=>false,
        'SMTPCrypto'=>'tls',
        'wordWrap'=>true,
        'wrapChars '=>76,
        'mailType'=>'text',
        'charset'=>'UTF-8',
        'validate'=>false,
        'priority'=>3,
        'CRLF'=>'\r\n',
        'newline'=>'\r\n',
        'BCCBatchMode'=>false,
        'BCCBatchSize'=>200,
        'DSN'=>false
    ]
];