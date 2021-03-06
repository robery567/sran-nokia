<?php

namespace App\EventSubscriber;

use App\Controller\AdminControllerInterface;
use App\Service\Login\LoginFactory;
use App\Service\Login\LoginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecuritySubscriber implements EventSubscriberInterface {

	/**
	 * @var LoginInterface
	 */
	protected $login;

	/**
	 * @var UrlGeneratorInterface
	 */
	protected $urlGenerator;

	/**
	 * @var RedirectResponse
	 */
	private $response;

	/**
	 * SecuritySubscriber constructor.
	 * @param LoginFactory $loginFactory
	 * @param UrlGeneratorInterface $urlGenerator
	 */
	public function __construct(LoginFactory $loginFactory, UrlGeneratorInterface $urlGenerator) {
		$this->login = $loginFactory->getLogin();
		$this->urlGenerator = $urlGenerator;
	}

	protected function shouldRedirectUser(): bool {
		if (!$this->login->isLogged()) {
			$this->response = $this->redirectToRoute('login_index');
			return true;
		}
		if (!$this->login->isAdmin()) {
			$this->response = $this->redirectToRoute('main_index');
			return true;
		}
		return false;
	}

	protected function redirectToCorrectPage(): RedirectResponse {
		return $this->response;
	}

	protected function redirectToRoute($route): RedirectResponse {
		return new RedirectResponse($this->urlGenerator->generate($route));
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents(): array {
		return [
			KernelEvents::CONTROLLER => [
				['denyAccessToAdminSection'],
			],
		];
	}

	/**
	 * @param FilterControllerEvent $event
	 */
	public function denyAccessToAdminSection(FilterControllerEvent $event): void {
		$controller = $event->getController();

		if (isset($controller[0]) && $controller[0] instanceof AdminControllerInterface && $this->shouldRedirectUser()) {
			$event->setController(function () {
				return $this->redirectToCorrectPage();
			});
		}
	}

}