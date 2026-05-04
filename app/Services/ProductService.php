<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function addImageToProduct(Product $product, string $path)
    {
        return $product->images()->create([
            'image_path' => $path
        ]);
    }

    public function uploadFile(UploadedFile $file, string $folder): string
    {
        // التخزين على S3 مع جعل الملف قابل للقراءة (public)
        return Storage::disk('s3')->put($folder, $file, 'public');
    }
}
