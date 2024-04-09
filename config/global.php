<?php
$domain = isset($_SERVER['SERVER_NAME'])? $_SERVER['SERVER_NAME'] : '';
$path=str_replace('.','_', $domain);
return [
    'APP_URL'=>trim(env('APP_URL') ?? ''),
    'FileViewPath'=> trim(env('APP_URL') ?? '').'/sms_uploads/Files/',
    'FileUploadPath'=> trim(base_path() ?? '').'/../sms_uploads/Files/',


];
