<?php

namespace Tallcms\Pro\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tallcms\Pro\Models\ProSetting;

class InjectGoogleAnalytics
{
    /**
     * Handle an incoming request.
     *
     * Injects Google Analytics gtag script into HTML responses.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        // Only process HTML responses
        if (! $response instanceof Response) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html') && ! empty($contentType)) {
            return $response;
        }

        // Skip admin routes
        if ($request->is('admin/*') || $request->is('livewire/*')) {
            return $response;
        }

        // Get the measurement ID
        $measurementId = ProSetting::get('google_analytics_id');

        if (empty($measurementId)) {
            return $response;
        }

        // Validate measurement ID format (G-XXXXXXXXXX)
        if (! preg_match('/^G-[A-Z0-9]+$/i', $measurementId)) {
            return $response;
        }

        $content = $response->getContent();

        // Only inject if we have a </head> tag
        if (! str_contains($content, '</head>')) {
            return $response;
        }

        $gtagScript = $this->getGtagScript($measurementId);

        // Inject before </head>
        $content = str_replace('</head>', $gtagScript.'</head>', $content);

        $response->setContent($content);

        return $response;
    }

    /**
     * Generate the gtag script HTML.
     */
    protected function getGtagScript(string $measurementId): string
    {
        $measurementId = e($measurementId);

        return <<<HTML

    <!-- Google Analytics (gtag.js) - TallCMS Pro -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={$measurementId}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{$measurementId}');
    </script>

HTML;
    }
}
