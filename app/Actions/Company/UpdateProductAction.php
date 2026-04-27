<?php

namespace App\Actions\Company;

use App\Actions\BaseAction;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use App\Jobs\UploadProductImagesToS3;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction extends BaseAction
{
    public function __construct(protected ProductService $productService) {}

    public function execute(Product $product, array $data, $company, ?array $newImages = [], ?array $imagesToDelete = []): Product
    {
        $allowedBooths = ['Kiosk AB', 'Kiosk CD'];
        if (isset($data['price']) && $data['price'] > 0 && !in_array($company->booth_type, $allowedBooths)) {
            throw new \Exception('نوع حجزك لا يدعم إضافة سعر.', 403);
        }

        return $this->executeAction(function () use ($product, $data, $newImages, $imagesToDelete) {
            $product->update($data);

            if (!empty($imagesToDelete)) {
                $this->deleteImages($imagesToDelete);
            }

            if (!empty($newImages)) {
                $tempPaths = array_map(fn($img) => $img->store('temp', 'local'), $newImages);
                UploadProductImagesToS3::dispatch($product, $tempPaths);
            }

            return $product->fresh('images');
        }, "تم تحديث المنتج: {$product->name}", [], true);
    }
    /**
     * حذف الصور
     */
    protected function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            Storage::disk('s3')->delete($image->image_path);
            $image->delete();
        }
    }
}
