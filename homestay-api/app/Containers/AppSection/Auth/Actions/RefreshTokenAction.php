<?php

namespace App\Containers\AppSection\Auth\Actions;

use App\Containers\AppSection\Authentication\Values\Clients\WebClient;
use App\Ship\Parents\Actions\Action;
use Illuminate\Http\Response;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Http;

class RefreshTokenAction extends Action
{
    /**
     * Refresh access token using refresh token
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function run(array $data): array
    {
        $refreshToken = $data['refresh_token'];

        try {
            // Get web client credentials
            $webClient = WebClient::create();

            // Prepare OAuth2 refresh token request using configured OAuth URL
            $oauthUrl = env('OAUTH_URL', 'http://honestay_nginx:80/v1/oauth/token');
            $response = Http::asForm()->post($oauthUrl, [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $webClient->id(),
                'client_secret' => $webClient->plainSecret(),
                'scope' => '',
            ]);

            $responseData = $response->json();

            if (!$response->successful()) {
                throw new \Exception(
                    'OAuth response: ' . ($responseData['message'] ?? $response->body() ?? 'Invalid or expired refresh token')
                );
            }

            // Get user information from the new access token
            $accessToken = $responseData['access_token'];
            $token = Token::where('id', $this->getTokenIdFromAccessToken($accessToken))->first();

            if (!$token) {
                throw new \Exception('Token not found');
            }

            $user = $token->user;

            return [
                'access_token' => $responseData['access_token'],
                'refresh_token' => $responseData['refresh_token'],
                'token_type' => $responseData['token_type'] ?? 'Bearer',
                'expires_in' => $responseData['expires_in'],
                'user' => [
                    'id' => $user->id,
                    'phone' => $user->phone,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'avatar' => $user->avatar,
                    'country_code' => $user->country_code,
                    'phone_code' => $user->phone_code,
                    'phone_number' => $user->phone_number,
                ],
            ];

        } catch (\Exception $e) {
            throw new \Exception('Failed to refresh token: ' . $e->getMessage());
        }
    }

    /**
     * Extract token ID from JWT access token
     *
     * @param string $accessToken
     * @return string|null
     */
    private function getTokenIdFromAccessToken(string $accessToken): ?string
    {
        try {
            // JWT tokens have 3 parts separated by dots
            $parts = explode('.', $accessToken);
            if (count($parts) !== 3) {
                return null;
            }

            // Decode the payload (second part)
            $payload = json_decode(base64_decode($parts[1]), true);

            return $payload['jti'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
