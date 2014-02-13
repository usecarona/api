<?php

/**
 * Authentication class file.
 *
 * @package api\components
 * @filesource
 */
Yii::import('application.modules.pas.modules.autenticacao.models.Token');

/**
 * Class utilized for to do authentication with api system.
 * Implementation realized with base in RFC 6749 (OAuth2) items 4.3, 4.4, 6, 7.1 ...
 * @author Gustavo Sávio <gustavo.savio@cotacoesecompras.com.br>
 */
class Authentication
{

	/**
	 * Property utilized for to store the requisition header.
	 * @var mixed
	 */
	protected $header;

	/**
	 * Property utilized for to store authorization data.
	 * @var string[]
	 */
	protected $authorization;

	/**
	 * Property utilized for to store client login.
	 * @var string
	 */
	protected $clientId;

	/**
	 * Property utilized for to store client password.
	 * @var string
	 */
	protected $clientSecret;

	/**
	 * Property utilized for to store post requisition.
	 * @var mixed[]
	 */
	protected $post;

	/**
	 * Construct class.
	 *
	 */
	public function __construct()
	{
		$this->header = Yii::app()->request->getRequestHeaders();
		$this->post = Yii::app()->request->getRestParams();
	}

	/**
	 * Validation method utilized for data manipulation.
	 *
	 * @throws CHttpException
	 */
	public function authenticate()
	{
		preg_match('/^(?<tipo>Basic) (?<secret>.+)$/', $this->header['Authorization'], $this->authorization);

		if (empty($this->authorization)) {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}

		list($this->clientId, $this->clientSecret) = explode(':', base64_decode($this->authorization['secret']));

		switch ($this->post['grant_type']) {
			case 'password':
				$this->authenticatePassword();
				break;
			case 'client_credentials':
				$this->authenticateClient();
				break;
			case 'refresh_token':
				$this->refreshToken();
				break;
			default:
				throw new CHttpException(400, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(400)));
				break;
		}
	}

	/**
	 * Implements validation for access type: password.
	 *
	 * @throws CHttpException
	 */
	public function authenticatePassword()
	{
		if (empty($this->post['username']) || empty($this->post['password'])) {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}

		$client = Sistema::model()->find('client_id = :client_id', [':client_id' => $this->clientId]);
		$user = Usuario::model()->find('login = :login', [':login' => $this->post['username']]);

		if (BCrypt::check($this->clientSecret, $client->client_secret) && BCrypt::check($this->post['password'], $user->senha)) {
			$this->validateToken();
			Yii::app()->response->sendResponse($this->generateToken());
		} else {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}
	}

	/**
	 * Implements validation for access type: client credentials.
	 *
	 * @throws CHttpException
	 */
	public function authenticateClient()
	{
		$client = Sistema::model()->find('client_id = :client_id', [':client_id' => $this->clientId]);

		if (BCrypt::check($this->clientSecret, $client->client_secret)) {
			$this->validateToken();
			Yii::app()->response->sendResponse($this->generateToken());
		} else {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}
	}

	/**
	 * Implements validation for access type: refresh token.
	 *
	 * @throws CHttpException
	 */
	public function refreshToken()
	{
		if (empty($this->post['refresh_token'])) {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}

		$previousToken = Token::model()->find('refresh_token = :refresh_token', [':refresh_token' => $this->post['refresh_token']]);

		if ($previousToken) {
			if ($previousToken->username) {
				$this->post['username'] = $previousToken->username;
			}

			$previousToken->delete();
			Yii::app()->response->sendResponse($this->generateToken());
		} else {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}
	}

	/**
	 * Validate token for access in api.
	 *
	 * @throws CHttpException
	 */
	public function validateToken()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('client_id = :client_id');
		if (isset($this->post['username'])) {
			$criteria->addCondition('username = :username');
			$criteria->params = array(':client_id' => $this->clientId, ':username' => $this->post['username']);
		} else {
			$criteria->addCondition('username is NULL');
			$criteria->params = array(':client_id' => $this->clientId);
		}

		$previousToken = Token::model()->find($criteria);
		if ($previousToken) {
			$registration = new DateTime($previousToken->data_cadastro);
			$now = new DateTime();

			$diff = strtotime($now->format('Y-m-d H:i:s')) - strtotime($registration->format('Y-m-d H:i:s'));

			if ($diff > $previousToken->expires_in) {
				$previousToken->delete();
			} else {
				throw new CHttpException(409, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(409)));
			}
		}
	}

	/**
	 * Generate token for access in api.
	 *
	 * @throws CHttpException
	 */
	public function generateToken()
	{
		$system = Sistema::model()->find('client_id =:client_id', [':client_id' => $this->clientId]);

		$generatedToken = [
			'access_token' => sha1(uniqid(rand(), true)),
			'token_type' => 'Bearer',
			'expires_in' => $system->expires_in,
			'refresh_token' => sha1(uniqid(rand(), true))
		];

		$token = new Token();
		$token->access_token = $generatedToken['access_token'];
		$token->token_type = $generatedToken['token_type'];
		$token->refresh_token = $generatedToken['refresh_token'];
		$token->client_id = $this->clientId;
		$token->expires_in = $generatedToken['expires_in'];

		if ($this->post['username']) {
			$token->username = $this->post['username'];
		}

		if ($token->save()) {
			return $generatedToken;
		} else {
			throw new CHttpException(500, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(500)));
		}
	}

	/**
	 * Validate token transmitted through header bearer.
	 *
	 * @throws CHttpException
	 */
	public static function validateAuth()
	{
		$header = Yii::app()->request->getRequestHeaders();

		preg_match('/^(?<tipo>Bearer) (?<secret>.+)$/', $header['Authorization'], $authorization);

		if (empty($authorization)) {
			throw new CHttpException(400, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(400)));
		}

		$token = Token::model()->find('access_token = :access_token', [':access_token' => $authorization['secret']]);

		if (empty($token->access_token)) {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}

		$registration = new DateTime($token->data_cadastro);
		$timeZone = new DateTimeZone('America/Fortaleza');
		$now = new DateTime(null, $timeZone);

		$diff = strtotime($now->format('Y-m-d H:i:s')) - strtotime($registration->format('Y-m-d H:i:s'));

		if ($diff > $token->expires_in) {
			throw new CHttpException(401, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(401)));
		}
	}

}

