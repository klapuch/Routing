<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Mask for path
 */
final class PathMask implements Mask {
	private const SEPARATOR = '/';
	private $source;
	private $uri;

	public function __construct(string $source, Uri\Uri $uri) {
		$this->source = $source;
		$this->uri = $uri;
	}

	public function parameters(): array {
		$sources = explode(self::SEPARATOR, parse_url($this->source, PHP_URL_PATH));
		$parameters = array_diff(explode(self::SEPARATOR, $this->uri->path()), $sources);
		return array_combine(
			preg_replace(
				'~{|}|\s\S+~',
				'',
				array_intersect_key($sources, $parameters)
			),
			$parameters
		);
	}
}