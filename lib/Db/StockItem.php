<?php

declare(strict_types=1);

namespace OCA\Lager\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;

/**
 * @method int getSlotId()
 * @method void setSlotId(int $slotId)
 * @method string getArticle()
 * @method void setArticle(string $article)
 * @method string|null getDescription()
 * @method void setDescription(?string $description)
 * @method int getQuantity()
 * @method void setQuantity(int $quantity)
 * @method string|null getEan()
 * @method void setEan(?string $ean)
 * @method int getCreatedAt()
 * @method void setCreatedAt(int $createdAt)
 * @method int getUpdatedAt()
 * @method void setUpdatedAt(int $updatedAt)
 */
class StockItem extends Entity implements JsonSerializable {
	protected int $slotId = 0;
	protected string $article = '';
	protected ?string $description = null;
	protected int $quantity = 0;
	protected ?string $ean = null;
	protected int $createdAt = 0;
	protected int $updatedAt = 0;

	public function jsonSerialize(): array {
		return [
			'id' => (int)$this->getId(),
			'slot_id' => $this->slotId,
			'article' => $this->article,
			'description' => $this->description,
			'quantity' => $this->quantity,
			'ean' => $this->ean,
			'created_at' => $this->createdAt,
			'updated_at' => $this->updatedAt,
		];
	}
}
