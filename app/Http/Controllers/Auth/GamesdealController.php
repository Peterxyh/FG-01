<?php
namespace App\Http\Controllers\Auth;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/1
 * Time: 15:01
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GamesdealController extends Controller
{
    /**
     * Login and save user
     */
    public function login(Request $request)
    {
        $_apiKey = config('login.gamesdeal.apikey');
        $_secret = config('login.gamesdeal.secret');
        $baseUrl = config('login.gamesdeal.hostname');
        $_apiUrl = $baseUrl . config('login.gamesdeal.apiurl');
        $callbackUrl = config('login.gamesdeal.callback');
        $temporaryCredentialsRequestUrl = $baseUrl."/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
        $adminAuthorizationUrl = $baseUrl.'/admin/oAuth_authorize';
        $accessTokenRequestUrl = $baseUrl.'/oauth/token';

        if (!$request->input('oauth_token') && $request->input('state') == 1)
        {
            session('state', 0);
        }

        try {
            $authType = (session('state') == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
            $oauthClient = new \OAuth($_apiKey, $_secret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
            $oauthClient->disableSSLChecks();

            if (config('login.gamesdeal.debug')) $oauthClient->enableDebug();

            if (!$request->input('oauth_token') && !session('state'))
            {
                $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
                session('secret', $requestToken['oauth_token_secret']);
                session('state', 1);

                return redirect($adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
            } else if (session('state') == 1) {
                $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
                $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
                session('state', 2);
                session('token', $accessToken['oauth_token']);
                session('secret', $accessToken['oauth_token_secret']);

                return redirect($callbackUrl);
            } else {
                $oauthClient->setToken(session('token'), session('secret'));

                $resourceUrl = "$_apiUrl/customers";
                $oauthClient->fetch($resourceUrl, array(), 'GET', array('Content-Type' => 'application/json'));
                $_customer = json_decode($oauthClient->getLastResponse());
                print_r($_customer);
            }

        } catch (\OAuthException $e) {
            print_r($e->getMessage());
            echo "&lt;br/&gt;";
            print_r($e->lastResponse);
        }
    }
}