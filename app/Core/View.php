<?php

namespace App\Core;

final class View
{
    /**
     * Template being rendered.
     *
     * @var string
     */
    protected string $template;

    /**
     * Initialize a new view context with a template path.
     *
     * @param string $template Relative path to the template file.
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /**
     * Render the template, returning its content.
     *
     * @param array $attributes Data made available to the view.
     * @return string The rendered template content.
     *
     * @throws \Exception If the template file does not exist.
     */
    public function render(array $attributes = []): string
    {
        if (!file_exists($this->template)) {
            throw new \Exception("View template not found: {$this->template}");
        }

        // Extract attributes to make them available as variables in the template
        extract($attributes, EXTR_SKIP);

        ob_start();

        require $this->template;

        return ob_get_clean();
    }

    /**
     * Escape output to prevent XSS attacks.
     *
     * @param string $content The content to escape.
     * @return string The escaped content.
     */
    public static function escape(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    }
}
