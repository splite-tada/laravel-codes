<?php

namespace Tests\Feature\Api\Controller\CsvDownloadSampleController;

use Tests\TestCase;

class DownloadTest extends TestCase
{
    /**
     * Success test
     */
    public function testSuccess(): void
    {
        $response = $this->get('/api/csv-download-sample');

        $response->assertOk()
            ->assertDownload()
            ->assertStreamedCsv([
                ['id', 'name', 'email'],
                [1, 'テスト太郎', 'admin@sample.com'],
                [2, 'テスト花子', 'admin2@sample.com']
            ]);
    }
}
