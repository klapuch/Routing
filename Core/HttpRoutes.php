<?php
declare(strict_types = 1);
namespace Klapuch\Routing;

use Klapuch\Uri;

/**
 * Routes suitable for HTTP protocol
 */
final class HttpRoutes implements Routes {
	private const SHORTCUTS = [
		':int' => '\d+',
		':id' => '[1-9][0-9]*',
		':string' => '\w*\d*',
	];
	private $choices;
	private $method;

	public function __construct(array $choices, string $method) {
		$this->choices = $choices;
		$this->method = $method;
	}

	public function match(Uri\Uri $uri): Route {
		$matches = array_filter(
			$this->patterns($this->choices),
			function(string $source) use ($uri): bool {
				return (bool) preg_match(
					sprintf(
						'~^%s$~iu',
						$this->withShortcuts($this->withMethod($source, $this->method))
					),
					$this->path($uri, $this->method)
				);
			}
		);
		if ($matches) {
			return new HttpRoute(
				(string) key($matches),
				$this->choices[key($matches)],
				$uri
			);
		}
		throw new \UnexpectedValueException(
			sprintf('HTTP route for "%s" does not exist', $this->path($uri, $this->method))
		);
	}

	/**
	 * Applied decoded shortcuts
	 * @param string $source
	 * @return string
	 */
	private function withShortcuts(string $source): string
	{
		return str_replace(
			array_keys(self::SHORTCUTS),
			self::SHORTCUTS,
			$source
		);
	}

	/**
	 * Applied decoded method
	 * @param string $source
	 * @param string $method
	 * @return string
	 */
	private function withMethod(string $source, string $method): string
	{
		static $replacement = '(\s\[(%s)\])';
		$modification = preg_replace(
			'~\s\[(\w+)\]$~',
			sprintf($replacement, '$1'),
			$source,
			-1,
			$matches
		);
		if ($matches === 0)
			return $source . sprintf($replacement, $method);
		return $modification;
	}

	/**
	 * Path within method
	 * @param \Klapuch\Uri\Uri $uri
	 * @param string $method
	 * @return string
	 */
	private function path(Uri\Uri $uri, string $method): string
	{
		return sprintf('%s [%s]', $uri->path(), $method);
	}

	/**
	 * All the variable placeholders replaced by patterns
	 * @param array $choices
	 * @return array
	 */
	private function patterns(array $choices): array {
		return array_combine(
			array_keys($choices),
			array_map([$this, 'filling'], array_keys($choices))
		);
	}

	// @codingStandardsIgnoreStart Used by array_map
	/**
	 * Filling variable placeholders
	 * @param string $choice
	 * @return string
	 */
	private function filling(string $choice): string {
		return implode(
			'/',
			array_map([$this, 'pattern'], explode('/', $choice))
		);
	}

	/**
	 * Placeholder replaced by pattern
	 * @param string $part
	 * @return string
	 */
	private function pattern(string $part): string {
		return preg_replace(
			'~{.+}~',
			rtrim(explode(' ', $part)[1] ?? '[\w\d]+', '}'),
			$part
		);
	} // @codingStandardsIgnoreEnd
}
