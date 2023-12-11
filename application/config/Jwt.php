<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['jwt_key'] = '4abda8346ce1eaed8c7c4b0db123af88132a62a40d9c7fac89f2bf322d5de3b7';
$config['jwt_algorithm'] = 'HS256';
$config['jwt_issuer'] = 'https://serverprovider.com';
$config['jwt_audience'] = 'https://serverclient.com';
$config['jwt_expire'] = 3600;