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
			'page' => $_GET['page'] ?? 1,
			'per_page' => $_GET['per_page'] ?? 10,
			'fields' => $_GET['fields'] ?? '',
		] + $_GET;
	}
}
