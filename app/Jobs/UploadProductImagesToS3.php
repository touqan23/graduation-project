<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadProductImagesToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Product $product,
        protected array $tempFilePaths // مصفوفة المسارات المؤقتة
    ) {}

    public function handle()
    {
        foreach ($this->tempFilePaths as $tempPath) {
            // 1. قراءة الملف من التخزين المؤقت ورفعه إلى S3
            $fileContent = Storage::disk('local')->get($tempPath);
            $fileName = basename($tempPath);
            $s3Path = "products/images/{$fileName}";

            Storage::disk('s3')->put($s3Path, $fileContent, 'public');

            // 2. تسجيل الصورة في قاعدة البيانات
            $this->product->images()->create([
                'image_path' => $s3Path
            ]);

            // 3. حذف الملف المؤقت من السيرفر المحلي
            Storage::disk('local')->delete($tempPath);
        }
    }
}
