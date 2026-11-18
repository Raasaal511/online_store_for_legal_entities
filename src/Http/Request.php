<?php

declare(strict_types=1);

namespace App\Http;

final class Request
{
    /**
     * @param array<string, mixed> $get
     * @param array<string, mixed> $post
     * @param array<string, mixed> $server
     */
    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $server,
    ) {
    }

    public static function fromGlobals(): self
    {
        return new self($_GET, $_POST, $_SERVER);
    }

    public function method(): string
    {
        return strtoupper((string) ($this->server['REQUEST_METHOD'] ?? 'GET'));
    }

    public function path(): string
    {
        $uri = (string) ($this->server['REQUEST_URI'] ?? '/');
        $path = parse_url($uri, PHP_URL_PATH);

        return $path === null || $path === '' ? '/' : $path;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function wantsJson(): bool
    {
        $accept = strtolower((string) ($this->server['HTTP_ACCEPT'] ?? ''));
        $requestedWith = strtolower((string) ($this->server['HTTP_X_REQUESTED_WITH'] ?? ''));

        return str_contains($accept, 'application/json') || $requestedWith === 'xmlhttprequest';
    }

    /**
     * @return array<string, mixed>
     */
    public function query(): array
    {
        return $this->get;
    }

    /**
     * @return array<string, mixed>
     */
    public function body(): array
    {
        return $this->post;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }
}
