<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ClientRequestTrait {
    public function requestClient(): Client {
        return new Client([
            'base_uri' => 'http://localhost:5000',
        ]);
    }
}