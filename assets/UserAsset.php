<?php

namespace app\assets;

class UserAsset extends AppAsset
{
    public $js = [
        'js/user/confirm-registration.js',
        'js/user/confirm-code.js',
        'js/user/resend-code.js',
        'js/user/login-return-errors.js',

    ];
    public $css = [
        'css/user/confirm-reg.css',
        'css/user/registr-and-auth.css',
    ];
}