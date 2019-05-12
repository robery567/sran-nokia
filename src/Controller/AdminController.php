<?php

namespace App\Controller;

use App\Form\GlobalRefreshTimeType;
use App\Repository\SettingsEntityRepository;
use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\NewDeviceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", )
 */
class AdminController extends AbstractAppController {

	/**
	 * @Route("/", name="admin_index", methods={"GET","POST"})
	 * @return Response
	 */
	public function indexAction(): Response {
		if ($this->shouldRedirectUser()) {
			return $this->redirectToCorrectPage();
		}

		return $this->render(
			'admin/admin.html.twig'
		);
	}

	/**
	 * @Route("/add-device", name="admin_add_device", methods={"GET","POST"})
	 * @param Request                $request
	 * @param EntityManagerInterface $entityManager
	 * @param SessionInterface       $session
	 * @return Response
	 */
	public function addDeviceAction(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response {
		if ($this->shouldRedirectUser()) {
			return $this->redirectToCorrectPage();
		}

		$form = $this->createForm(NewDeviceType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($form->getData());
			$entityManager->flush();
			$session->set('message', 'The device has been added successfully');
			return $this->redirectToRoute('admin_add_device');
		}

		$message = null;
		if ($session->has('message')) {
			$message = $session->get('message');
			$session->remove('message');
		}

		return $this->render(
			'admin/add-device.html.twig',
			[
				'form' => $form->createView(),
				'message' => $message
			]
		);
	}

	/**
	 * @Route("/users", name="admin_users", methods={"GET","POST"})
	 * @param UserEntityRepository   $userEntityRepository
	 * @param Request                $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function usersAction(UserEntityRepository $userEntityRepository, Request $request, EntityManagerInterface $entityManager): Response {
		if ($this->shouldRedirectUser()) {
			return $this->redirectToCorrectPage();
		}

		foreach ($request->get('normalUsers', []) as $user) {
			$dbUser = $userEntityRepository->find($user);
			if ($dbUser !== null) {
				$dbUser->setIsAdmin(true);
				$entityManager->persist($dbUser);
			}
		}
		foreach ($request->get('adminUsers', []) as $user) {
			$dbUser = $userEntityRepository->find($user);
			if ($dbUser !== null) {
				$dbUser->setIsAdmin(false);
				$entityManager->persist($dbUser);
			}
		}
		$entityManager->flush();

		return $this->render(
			'admin/users.html.twig',
			[
				'normalUsers' => $userEntityRepository->findBy(['isAdmin' => 0]),
				'adminUsers' => $userEntityRepository->findBy(['isAdmin' => 1])
			]

		);
	}

	/**
	 * @Route("/refreshTime", name="admin_set_refreshTime", methods={"GET","POST"})
	 * @param Request                  $request
	 * @param EntityManagerInterface   $entityManager
	 * @param SessionInterface         $session
	 * @param SettingsEntityRepository $settingsEntityRepository
	 * @return Response
	 */
	public function refreshTimeAction(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, SettingsEntityRepository $settingsEntityRepository): Response {
		if ($this->shouldRedirectUser()) {
			return $this->redirectToCorrectPage();
		}

		$form = $this->createForm(
			GlobalRefreshTimeType::class,
			$settingsEntityRepository->findOneBy([], ['id' => 'DESC']));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($form->getData());
			$entityManager->flush();
			$session->set('message', 'The global refresh time has been update successfully');
			return $this->redirectToRoute('admin_set_refreshTime');
		}

		$message = null;
		if ($session->has('message')) {
			$message = $session->get('message');
			$session->remove('message');
		}

		return $this->render(
			'admin/global-refresh-time.html.twig',
			[
				'form' => $form->createView(),
				'message' => $message
			]
		);
	}
}