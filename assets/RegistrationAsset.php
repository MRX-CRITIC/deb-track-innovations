<?php

namespace app\assets;

class RegistrationAsset extends AppAsset
{
    public $js = [
        'js/user/confirm-registration.js',
        'js/user/confirm-code.js',
        'js/user/resend-code.js',
    ];
    public $css = [
        'css/user/confirm-reg.css',
        'css/user/registr-and-auth.css',
    ];
}