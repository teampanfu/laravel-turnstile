<?php

declare(strict_types=1);

namespace Panfu\Laravel\Turnstile;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class Turnstile
{
    /**
     * The Cloudflare Turnstile verification endpoint.
     */
    private const SITEVERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * The HTTP client instance.
     */
    private Client $client;

    /**
     * List of previously validated tokens.
     *
     * @var array<string>
     */
    private array $validatedResponses = [];

    /**
     * Create a new Turnstile instance.
     *
     * @throws \InvalidArgumentException If secret is empty
     */
    public function __construct(
        private readonly string $secret
    ) {
        if (empty($secret)) {
            throw new \InvalidArgumentException('Turnstile secret key cannot be empty');
        }
        $this->client = new Client;
    }

    /**
     * Validate a Turnstile response token.
     *
     * @throws GuzzleException When the HTTP request fails
     * @throws \RuntimeException When the validation response contains errors
     */
    public function validate(?string $token, ?string $ip = null): bool
    {
        if (empty($token)) {
            return false;
        }

        if (in_array($token, $this->validatedResponses, true)) {
            return true;
        }

        $response = $this->client->post(self::SITEVERIFY_URL, [
            'json' => [
                'secret' => $this->secret,
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);

        if ($result['success'] ?? false) {
            $this->validatedResponses[] = $token;

            return true;
        }

        if (isset($result['error-codes'])) {
            throw new \RuntimeException(
                'Challenge verification failed ('.implode(', ', $result['error-codes']).')'
            );
        }

        return false;
    }
}
