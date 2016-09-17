<?php

namespace xdrew\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Odnoklassniki extends AbstractProvider
{
    protected $OAuthURL = 'https://www.odnoklassniki.ru/oauth/authorize';
    protected $baseUri = 'http://api.odnoklassniki.ru';

    /**
     * Public application key
     * ex: CBAPKGGLEBACACACA
     * @var string
     */
    public $clientPublic;


    /**
     * @type array
     * @see https://apiok.ru/wiki/pages/viewpage.action?pageId=42476522
     */
    public $scopes = [
//        'GET_EMAIL', // Доступ к email адресу пользователя
//        'APP_INVITE', // Разрешение, чтобы приглашать друзей в игру методом friends.appInvite
//        'EVENTS', // Доступ к отсылке оповещений пользователю
//        'GROUP_CONTENT', // Доступ к группам
//        'LONG_ACCESS_TOKEN', // Получение длинных токенов от OAuth авторизации (https://apiok.ru/wiki/pages/viewpage.action?pageId=42476652)
//        'PHOTO_CONTENT', // Доступ к фотографиям
//        'VALUABLE_ACCESS', // Основное разрешение, необходимо для вызова большинства методов
//        'VIDEO_CONTENT', // Доступ к видео
    ];

    /**
     * @type array
     * @see https://apiok.ru/wiki/display/api/fields+ru
     */
    public $userFields = [
        'uid',
        'locale',
        'first_name',
        'last_name',
        'name',
        'gender',
        'pic_3',
        'location',
        'birthday',

//        'age',
//        'has_email',
//        'current_location',
//        'current_status',
//        'current_status_id',
//        'current_status_track_id',
//        'current_status_date',
//        'current_status_date_ms',
//        'online',
//        'last_online',
//        'last_online_ms',
//        'photo_id',
//        'pic_1',
//        'pic_2',
//        'pic_3',
//        'pic_4',
//        'pic_5',
//        'pic50x50',
//        'pic128x128',
//        'pic128max',
//        'pic180min',
//        'pic240min',
//        'pic320min',
//        'pic190x190',
//        'pic640x480',
//        'pic1024x768',
//        'pic1024max',
//        'url_profile',
//        'url_chat',
//        'url_profile_mobile',
//        'url_chat_mobile',
//        'can_vcall',
//        'can_vmail',
//        'email', // GET_EMAIL permission is required
//        'allows_anonym_access',
//        'allows_messaging_only_for_friends',
//        'registered_date',
//        'registered_date_ms',
//        'premium',
//        'show_lock',
//        'has_service_invisible',
//
//        // applies for users.getUserInfoBy
//        'friend',
//        'blocks', // true if user blocks current user
//        'blocked', // true if user is blocked by current user
//        'accessible', // true if users profile is accessible by current user
//        'relationship', // see relationship type fields
//        'friend_invitation',
//        'group_invite_allowed', // returned as "capabilities":"gi"
//        'friend_invite_allowed', // returned as "capabilities":"fi"
//        'send_message_allowed', // returned as "capabilities":"sm"
    ];

    public function getBaseAuthorizationUrl()
    {
        return $this->OAuthURL;
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseUri . "/oauth/token.do";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $signed_params = [
            'application_key' => $this->clientPublic,
            'method' => 'users.getCurrentUser',
            'fields' => $this->userFields,
        ];

        $signature = md5(str_replace('&', '', $this->buildQueryString($signed_params)) . md5($token->getTokent() . $this->clientSecret));

        $params = $signed_params + ['sig' => $signature, 'access_token' => $token->getToken()];
        $query = $this->buildQueryString($params);
        $url = "$this->baseUri/fb.do?$query";

        return $url;
    }

    protected function getDefaultScopes()
    {
        return $this->scopes;
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // Metadata info
        $contentTypeRaw = $response->getHeader('Content-Type');
        $contentTypeArray = explode(';', reset($contentTypeRaw));
        $contentType = reset($contentTypeArray);
        // Response info
        $responseCode    = $response->getStatusCode();
        $responseMessage = $response->getReasonPhrase();
        // Data info
        $error = !empty($data['error']) ? $data['error'] : null;
        $errorCode = !empty($error['error_code']) ? $error['error_code'] : $responseCode;
        $errorDescription = !empty($data['error_description']) ? $data['error_description'] : null;
        $errorMessage = !empty($error['error_msg']) ? $error['error_msg'] : $errorDescription;
        $message = $errorMessage ?: $responseMessage;

        // Request/meta validation
        if (399 < $responseCode) {
            throw new IdentityProviderException($message, $responseCode, $data);
        }

        // Content validation
        if ('application/json' != $contentType) {
            throw new IdentityProviderException($message, $responseCode, $data);
        }
        if ($error) {
            throw new IdentityProviderException($errorMessage, $errorCode, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new User($response['response']);
    }
}
