<?php

namespace app\assets;

class ConfirmRegistrationAsset extends AppAsset
{
    public $js = [
        'js/registration/confirm-registration.js',
        'js/registration/confirm-code.js',
        'js/registration/resend-code.js',
    ];
    public $css = [
        'css/user/confirm-reg.css',
        'css/user/registr-and-auth.css',
    ];
}