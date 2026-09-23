<?php

declare(strict_types=1);

namespace OCA\Lager\Controller;

use OCA\Lager\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IGroupManager;
use OCP\IRequest;
use OCP\IUserSession;
use OCP\IURLGenerator;

class PageController extends Controller {
	public function __construct(
		IRequest $request,
		private IURLGenerator $urlGenerator,
		private IUserSession $userSession,
		private IGroupManager $groupManager,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function main(): TemplateResponse {
		// App-l10n-Datei (l10n/{lang}.js) wird vom Core automatisch
		// vor das erste Util::addScript('lager', ...) im Template geladen.
		return new TemplateResponse(
			Application::APP_ID,
			'main',
			['isAdmin' => $this->isAdmin()],
			'user',
			200,
			['Feature-Policy' => "camera 'self';microphone 'self'"]
		);
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function redirect(): RedirectResponse {
		return new RedirectResponse($this->urlGenerator->linkToRoute('lager_page_main'));
	}

	private function isAdmin(): bool {
		$user = $this->userSession->getUser();
		return $user !== null && $this->groupManager->isAdmin($user->getUID());
	}
}
