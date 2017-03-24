<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

/**
 * Route mapped to real code
 */
final class MappedRoute implements Route {
	private const NAMESPACE_SEPARATOR = '\\',
		PATH_SEPARATOR = '/';
	private $origin;
	private $namespace;

	public function __construct(Route $origin, string $namespace) {
		$this->origin = $origin;
		$this->namespace = $namespace;
	}

	public function resource(): string {
		return $this->withNamespace(
			trim($this->namespace, self::NAMESPACE_SEPARATOR),
			$this->toClass($this->origin->resource(), $this->origin->action())
		);
	}

	public function action(): string {
		return $this->origin->action();
	}

	public function parameters(): array {
		return $this->origin->parameters();
	}

	private function withNamespace(string $namespace, string $class): string {
		return self::NAMESPACE_SEPARATOR . $namespace . self::NAMESPACE_SEPARATOR .
			str_replace(self::PATH_SEPARATOR, self::NAMESPACE_SEPARATOR, $class);
	}

	private function toClass(string $resource, string $action): string {
		return implode(
			self::PATH_SEPARATOR,
			array_map('ucfirst', [$resource, $action])
		);
	}

}