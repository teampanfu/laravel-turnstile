<?php

namespace Panfu\Laravel\Turnstile;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Turnstile
{
    /**
     * The Turnstile siteverify endpoint.
     *
     * @var string
     */
    const SITEVERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * The secret key used for validation with Cloudflare's Turnstile service.
     *
     * @var string
     */
    private $secret;

    /**
     * An array holding already validated responses to prevent re-validation.
     *
     * @var array
     */
    private $validatedResponses = [];

    /**
     * Create a new Turnstile instance with the provided secret key.
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Validate a response token with Cloudflare's Turnstile service.
     */
    public function validate(?string $token, string $remoteip = null): bool
    {
        if (empty($token)) {
            return false;
        }

        // Check if the response has already been validated.
        if (in_array($token, $this->validatedResponses)) {
            return true;
        }

        $client = new Client();
        $body = [
            'secret' => $this->secret,
            'response' => $token,
            'remoteip' => $remoteip,
        ];

        try {
            $response = $client->post(self::SITEVERIFY_URL, ['json' => $body]);
            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['success']) && $responseData['success'] === true) {
                $this->validatedResponses[] = $token;

                return true;
            } else {
                return false;
            }
        } catch (RequestException $e) {
            return false;
        }
    }
}
