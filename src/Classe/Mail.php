<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
    private $api_key = '03e857705165e13eb2c31f1768748525';
    private $api_key_secret = 'fca025d451cf4210768f4b90c6db1e12';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "aimericjjb@gmail.com",
                        'Name' => "La boutique française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3156203,
                    'TemplateLanguage' => true,
                    'Subject' => $subject, 
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    } 
}