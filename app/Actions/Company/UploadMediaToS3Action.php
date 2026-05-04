<?php

namespace App\Actions\Company;

use Illuminate\Support\Facades\Storage;

class UploadMediaToS3Action
{
public function execute($file, $path = 'globalpages')
{
// استخدام S3 Storage
$filePath = Storage::disk('s3')->put($path, $file, 'public');
return $filePath; // سيخزن هذا المسار في جدول الـ settings
}
}
