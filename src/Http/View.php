<?php

declare(strict_types=1);

namespace App\Http;

final class View
{
    public function __construct(private readonly string $basePath)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $content = $this->renderFile($template, $data);

        if ($layout === null) {
            return $content;
        }

        return $this->renderFile($layout, array_merge($data, ['content' => $content]));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderFile(string $template, array $data): string
    {
        $path = $this->basePath . '/' . ltrim($template, '/') . '.php';

        if (!is_file($path)) {
            throw new \RuntimeException("Template not found: {$template}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $path;

        return (string) ob_get_clean();
    }
}
