<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

/**
 * Fake
 */
final class FakeRoute implements Route {
	private $resource;
	private $action;
	private $parameters;

	public function __construct(
		string $resource = null,
		string $action = null,
		array $parameters = null
	) {
		$this->resource = $resource;
		$this->action = $action;
		$this->parameters = $parameters;
	}

	public function resource(): string {
		return $this->resource;
	}

	public function action(): string {
		return $this->action;
	}

	public function parameters(): array {
		return $this->parameters;
	}
}