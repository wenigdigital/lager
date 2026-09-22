<?php

declare(strict_types=1);

namespace OCA\Lager\Controller;

use OCA\Lager\AppInfo\Application;
use OCA\Lager\Service\LagerService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\Response;
use OCP\IL10N;
use OCP\IRequest;

class ApiController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private LagerService $service,
		private IL10N $l10n,
	) {
		parent::__construct($appName, $request);
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function tree(): JSONResponse {
		return $this->handle(function (): array {
			return $this->service->getTree();
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function stockBySlot(int $slot_id): JSONResponse {
		return $this->handle(function () use ($slot_id): array {
			return [
				'slot' => $this->service->getSlot($slot_id),
				'items' => $this->service->getStockBySlot($slot_id),
			];
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function history(int $limit = 100, ?string $slot_id = null, ?string $article = null): JSONResponse {
		return $this->handle(function () use ($limit, $slot_id, $article): array {
			return $this->service->getHistory($limit, $slot_id, $article);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function search(string $q = ''): JSONResponse {
		return $this->handle(function () use ($q): array {
			return $this->service->searchStock($q);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function createLocation(string $name, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($name, $description): array {
			return $this->service->createLocation($name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function updateLocation(int $id, ?string $name = null, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($id, $name, $description): array {
			return $this->service->updateLocation($id, $name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function deleteLocation(int $id): JSONResponse {
		return $this->handle(function () use ($id): array {
			$this->service->deleteLocation($id);
			return ['ok' => true];
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function createCabinet(int $location_id, string $name, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($location_id, $name, $description): array {
			return $this->service->createCabinet($location_id, $name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function updateCabinet(int $id, ?string $name = null, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($id, $name, $description): array {
			return $this->service->updateCabinet($id, $name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function deleteCabinet(int $id): JSONResponse {
		return $this->handle(function () use ($id): array {
			$this->service->deleteCabinet($id);
			return ['ok' => true];
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function createSlot(int $cabinet_id, string $name, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($cabinet_id, $name, $description): array {
			return $this->service->createSlot($cabinet_id, $name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function updateSlot(int $id, ?string $name = null, ?string $description = null): JSONResponse {
		return $this->handle(function () use ($id, $name, $description): array {
			return $this->service->updateSlot($id, $name, $description);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function deleteSlot(int $id): JSONResponse {
		return $this->handle(function () use ($id): array {
			$this->service->deleteSlot($id);
			return ['ok' => true];
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function createStock(int $slot_id, string $article, ?string $description = null, int $quantity = 0, ?string $ean = null): JSONResponse {
		// Fallback: read from JSON body if parameter not resolved
		if ($ean === null) {
			$ean = $this->getJsonParam('ean');
		}
		return $this->handle(function () use ($slot_id, $article, $description, $quantity, $ean): array {
			return $this->service->createStock($slot_id, $article, $description, $quantity, $ean);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function updateStock(int $id, ?string $article = null, ?string $description = null, ?string $ean = null): JSONResponse {
		// Fallback: read from JSON body if parameter not resolved
		if ($ean === null && $this->request->getHeader('Content-Type') !== '') {
			$ean = $this->getJsonParam('ean');
		}
		return $this->handle(function () use ($id, $article, $description, $ean): array {
			return $this->service->updateStock($id, $article, $description, $ean);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function deleteStock(int $id): JSONResponse {
		return $this->handle(function () use ($id): array {
			$this->service->deleteStock($id);
			return ['ok' => true];
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function createMovement(int $stock_id, string $type, int $quantity, ?string $note = null): JSONResponse {
		return $this->handle(function () use ($stock_id, $type, $quantity, $note): array {
			return $this->service->moveStock($stock_id, $type, $quantity, $note);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function scanByCode(string $code): JSONResponse {
		return $this->handle(function () use ($code): array {
			$result = $this->service->searchByCode($code);
			if ($result === null) {
				throw new DoesNotExistException($this->l10n->t('No article found for this code.'));
			}
			return $result;
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function uploadStockImage(int $id): JSONResponse {
		return $this->handle(function () use ($id): array {
			$file = $this->request->getUploadedFile('file');
			if (!is_array($file) || (int)($file['error'] ?? \UPLOAD_ERR_NO_FILE) !== \UPLOAD_ERR_OK || empty($file['tmp_name'])) {
				throw new \InvalidArgumentException($this->l10n->t('No image uploaded.'));
			}
			if ((int)$file['size'] > 10 * 1024 * 1024) {
				throw new \InvalidArgumentException($this->l10n->t('Image is too large (max. 10 MB).'));
			}
			return $this->service->saveItemImage($id, (string)$file['tmp_name']);
		});
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function getStockImage(int $id): Response {
		$data = $this->service->getItemImage($id);
		if ($data === null) {
			// no-store: ein fehlendes Bild darf NICHT im Browser-Cache landen,
			// sonst bliebe das Thumbnail nach spaeterem Upload unsichtbar.
			return new DataResponse(['error' => $this->l10n->t('Not found.')], Http::STATUS_NOT_FOUND, [
				'Cache-Control' => 'no-store',
			]);
		}
		return new DataDisplayResponse($data, Http::STATUS_OK, [
			'Content-Type' => 'image/jpeg',
			'Cache-Control' => 'no-cache',
		]);
	}

	private function getJsonParam(string $key): ?string {
		$params = $this->request->getParams();
		if (isset($params[$key]) && $params[$key] !== '') {
			return (string)$params[$key];
		}
		// Try to parse raw JSON body
		$body = $this->request->getParam($key);
		if ($body !== null && $body !== '') {
			return (string)$body;
		}
		return null;
	}

	private function handle(callable $fn): JSONResponse {
		try {
			return new JSONResponse($fn());
		} catch (DoesNotExistException $e) {
			return new JSONResponse(['error' => $this->l10n->t('Not found.')], Http::STATUS_NOT_FOUND);
		} catch (\InvalidArgumentException $e) {
			return new JSONResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		} catch (\Throwable $e) {
			return new JSONResponse(['error' => $this->l10n->t('Unexpected error: ') . $e->getMessage()], Http::STATUS_INTERNAL_SERVER_ERROR);
		}
	}
}
