<?php

$sMetadataVersion = '2.1';

$aModule = [
    'id'           => '1und1oxidsmartinboxconnector',
    'title'        => [
        'de' => '1&1 Oxid Smart Inbox Connector',
        'en' => '1&1 Oxid Smart Inbox Connector',
    ],
    'thumbnail'    => 'logo.png',
    'description'  => [
        'de' => 'Erweitert die Oxid Transaktions-Mails mit Schema.org konformen HTML Inhalt',
        'en' => 'Extends Oxid transaction mails with a Schema.org conform HTML content.',
    ],
    'version'      => '0.1.0',
    'author'       => 'Flagbit GmbH & Co. KG',
    'email'        => 'info@flagbit.de',
    'blocks' => [
        ['template' => 'email/html/order_cust.tpl', 'block'=>'email_html_order_cust_orderemail', 'file'=>'/views/block/email/html/order_schema.tpl'],
        ['template' => 'email/html/ordershipped.tpl', 'block'=>'email_html_ordershipped_infofooter', 'file'=>'/views/block/email/html/order_schema.tpl'],
    ],
    'smartyPluginDirectories' => [
        '/Renderer/Smarty/',
    ],
];
