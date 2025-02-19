<?php

return [

    'title' => 'Нэвтрэх',

    'heading' => 'Нэвтрэх',

    'actions' => [

        'register' => [
            'before' => '',
            'label' => 'Шинээр бүртгүүлэх',
        ],

        'request_password_reset' => [
            'label' => 'Нууц үг мартсан?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'И-мэйл',
        ],

        'password' => [
            'label' => 'Нууц үг',
        ],

        'remember' => [
            'label' => 'Намайг сана',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Нэвтрэх',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'Нэр болон Нууц үгээ шалгана уу.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Түр хугцааны дараа нэвтэрнэ үү',
            'body' => 'Дахин оролдно уу :seconds секунд.',
        ],

    ],

];