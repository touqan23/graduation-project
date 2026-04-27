<?php
namespace App\Actions\Company;

use App\Actions\BaseAction;
use App\Models\Product;
use App\Jobs\UploadProductImagesToS3;
use App\Services\ProductService;

class CreateProductAction extends BaseAction
{
    public function __construct(
        protected ProductService $productService,
    )
    {
    }

    public function execute(array $data, ?array $images = [], $company): Product
    {
        // نقل منطق التحقق إلى هنا
        $allowedBooths = ['Kiosk AB', 'Kiosk CD'];
        if (isset($data['price']) && !in_array($company->booth_type, $allowedBooths)) {
            throw new \Exception('لا يمكنك إضافة سعر للمنتجات، نوع حجزك لا يدعم البيع.', 403);
        }

        return $this->executeAction(function () use ($data, $images, $company) {
            $data['company_id'] = $company->id;
            $product = Product::create($data);

            if (!empty($images)) {
                $tempPaths = array_map(fn($img) => $img->store('temp', 'local'), $images);
                UploadProductImagesToS3::dispatch($product, $tempPaths);
            }

            return $product;
        }, "تم إضافة منتج جديد: {$data['name']}", ['event_type' => 'product_creation'], true);
    }
}
