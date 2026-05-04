<?php
namespace App\Actions\Company;

use app\Actions\General\BaseAction;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction extends BaseAction
{
    public function execute(Product $product): bool
    {
         //عم اجيبهم مشان لما خزنهم باللوق ما يضرب المودل ايررور
        $productNameAr = $product->getTranslation('name', 'ar');
        $productNameEn = $product->getTranslation('name', 'en');

        return $this->executeAction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('s3')->delete($image->image_path);
            }
            $product->images()->delete();
            return $product->delete();

        }, [
            'ar' => "تم حذف المنتج: {$productNameAr}",
            'en' => "Product deleted: {$productNameEn}"
        ], ['event_type' => 'product_deletion'], true);
    }
}
