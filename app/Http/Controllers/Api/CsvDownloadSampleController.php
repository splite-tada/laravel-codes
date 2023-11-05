<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CsvDownloadSampleController extends Controller
{
    public function download()
    {
        $csvContents = [
            ['id', 'name', 'email'],
            [1, 'テスト太郎', 'admin@sample.com'],
            [2, 'テスト花子', 'admin2@sample.com']
        ];

        return response()->csv('sample.csv', $csvContents);
    }
}
