<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __construct(string $name)
    {
        parent::__construct($name);

        /**
         * ダウンロードしたCSVファイルの中身が正しいかテストする
         *
         * @param array $expected 期待値
         * @param string $streamedContent ダウンロードしたCSVファイルの中身
         * @return void
         */
        TestResponse::macro('assertStreamedCsv', function (array $expected) {
            // 文字列エンコード -> 改行コード統一 -> 改行コードで分割して配列にする
            $csvResponseArray = explode(
                "\n",
                str_replace(
                    ["\r\n", "\r", "\n"],
                    "\n",
                    mb_convert_encoding($this->streamedContent, 'utf-8', 'sjis')
                )
            );

            if (!end($csvResponseArray)) {
                // 配列最後が空なら最後は削除（最後の改行コード分調整）
                array_pop($csvResponseArray);
            }

            foreach ($csvResponseArray as $rowNumber => $csvResponseRow) {
                PHPUnit::assertEquals($expected[$rowNumber], str_getcsv($csvResponseRow));
            }

            return $this;
        });
    }
}
