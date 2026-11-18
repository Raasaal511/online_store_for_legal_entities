<?php

declare(strict_types=1);

namespace App\Http;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $path = rtrim($request->path(), '/') ?: '/';

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            return Response::html($this->notFoundHtml(), 404);
        }

        $result = $handler($request);

        if (!$result instanceof Response) {
            throw new \RuntimeException('Route handler must return Response.');
        }

        return $result;
    }

    private function notFoundHtml(): string
    {
        return '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>404</title></head>'
            . '<body style="font-family:sans-serif;padding:2rem"><h1>Страница не найдена</h1>'
            . '<p><a href="/">На главную</a></p></body></html>';
    }
}
