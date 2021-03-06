<?php


namespace App\Service\Login;


use App\Entity\LoginEntity;
use App\Entity\UserEntity;

interface LoginInterface {

	/**
	 * @throws LoginException
	 * @param LoginEntity $loginEntity
	 */
	public function authenticate(LoginEntity $loginEntity) : void;

	public function getPrincipal() : ?UserEntity;

	public function isLogged() : bool;

	public function isAdmin() : bool;

	public function logOut() : void;

}