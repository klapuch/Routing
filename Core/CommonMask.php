<?php
declare(strict_types = 1);

namespace Klapuch\Routing;

/**
 * Mask common for all requests
 */
final class CommonMask implements Mask {
	public function parameters(): array {
		return [
			'sort' => $_GET['sort'] ?? '',
			'page' => intval($_GET['page'] ?? 1) ?: 1,
			'per_page' => intval($_GET['per_page'] ?? 10) ?: 10,
			'fields' => $_GET['fields'] ?? '',
		] + $_GET;
	}
}
