<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('csv', function (string $fileName, array $contents = []) {
            $response = new StreamedResponse(function () use ($contents) {
                $stream = fopen('php://output', 'w');
                // 文字化け回避(文字列をUTF-8へ変換しマルチバイト文字が入っていても文字化けしないようにする)
                stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
                foreach ($contents as $contentRow) {
                    fputcsv($stream, $contentRow);
                }
                fclose($stream);
            });

            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName);

            return $response;
        });
    }
}
