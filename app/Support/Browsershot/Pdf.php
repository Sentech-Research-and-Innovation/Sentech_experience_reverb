<?php

namespace App\Support\Browsershot;

use Illuminate\Http\Response;
use Spatie\Browsershot\Browsershot;

/**
 * Minimal PDF::loadView()->margins()->download() replacement for the
 * abandoned-dependency-chain verumconsilium/laravel-browsershot wrapper,
 * built directly on spatie/browsershot. Only the subset of the API this
 * app actually uses is implemented; unknown method calls are delegated
 * to the underlying Browsershot instance, matching the wrapper's own
 * __call() behavior.
 */
class Pdf
{
    protected Browsershot $browsershot;

    protected function __construct()
    {
        $this->browsershot = Browsershot::html('');
    }

    public static function loadView(string $view, ?array $data = [], ?array $mergeData = []): static
    {
        $instance = new static();
        $html = view($view, $data ?? [], $mergeData ?? [])->render();
        $instance->browsershot->setHtml($html);

        return $instance;
    }

    public function download(?string $filename = null, array $additionalHeaders = []): Response
    {
        $contents = $this->browsershot->pdf();

        $disposition = 'attachment';
        if ($filename !== null) {
            $disposition .= '; filename="'.$filename.'"';
        }

        return new Response($contents, 200, array_merge([
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition,
        ], $additionalHeaders));
    }

    public function __call($name, $arguments)
    {
        $this->browsershot->$name(...$arguments);

        return $this;
    }
}
