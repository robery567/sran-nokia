<?php


namespace App\Service\Login;


use App\Entity\LoginEntity;

class FakeLogin extends LdapLogin {

	/**
	 * @param LoginEntity $loginEntity
	 */
	public function authenticate(LoginEntity $loginEntity): void {
		$this->setAuthenticatedUser($loginEntity);
	}
}