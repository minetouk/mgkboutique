<?php

namespace App\Classe;
use Mailjet\Client;
use \Mailjet\Resources;

class Mail
{
    private $api_key = '5372efdd085d754045f50105949d92c7';
    private $api_key_secret = '44c6906bd5f4565bc04cd780a97968f1';

    public function send($to_email, $to_name, $subject,$content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "minetoukeita7@gmail.com",
                        'Name' => "minetou"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => "$to_name"
                        ]
                    ],
                    'TemplateID' => 6211731,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,

                    'Variables' => [
                        'content' => $content,
                        
                    ]
                ]

            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
          $response->success();
    }
}
