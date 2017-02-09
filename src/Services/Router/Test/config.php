<?php
[
    '/home/post' => [
        'get' => [
            'target' => 'Home@post',
        ],
        'post' => [
            'target' => 'Home@new',
        ],
    ],
    '/post/{post_id}/comment/{comment_id}' => [
        'get' => [
            'target' => 'Post@commnet',
            'pattern' => ['post_id'=>'xx', 'comment'=>'xxx'],
        ]
    ]
];

$name = [
    'post' => 'url'
];

$group = [
    'group1' => ['post', 'xxx'],
];
