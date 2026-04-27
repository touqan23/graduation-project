<?php
namespace App\Actions\Company;

use App\Actions\BaseAction;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction extends BaseAction
{
    public function execute(Product $product): bool
    {
        return $this->executeAction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('s3')->delete($image->image_path);
            }
            $product->images()->delete();
            return $product->delete();

        }, "تم حذف المنتج: {$product->name}");
    }
}
