<?php

return [

    'title' => 'Шинээр бүртгүүлэх',

    'heading' => 'Хэрэглэгч бүртгэх',

    'actions' => [

        'login' => [
            'before' => '',
            'label' => 'Өөрийн бүртгэлээр нэвтрэх',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'И-мэйл',
        ],

        'name' => [
            'label' => 'Хэрэглэгчийн нэр',
        ],

        'password' => [
            'label' => 'Нууц үг',
            'validation_attribute' => 'Нууц үг',
        ],

        'password_confirmation' => [
            'label' => 'Нууц үг баталгаажуулах',
        ],

        'actions' => [

            'register' => [
                'label' => 'Бүртгүүлэх',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Түр хугцааны дараа нэвтэрнэ үү',
            'body' => 'Дахин оролдно уу :seconds секунд.',
        ],

    ],

];