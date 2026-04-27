<?php

namespace App\Http\Controllers;

use App\Actions\company\CreateProductAction;
use App\Actions\Company\DeleteProductAction;
use App\Actions\Company\DeletePromotionAction;
use App\Actions\Company\GetCompanyDashboardStatsAction;
use App\Actions\Company\GetCompanyProductsAction;
use App\Actions\Company\GetCompanyPromotionsAction;
use App\Actions\Company\GetGlobalSettingsAction;
use App\Actions\Company\PromoteApprovedRequestsAction;
use App\Actions\Company\StoreCompanyRequestAction;
use App\Actions\Company\StorePromotionAction;
use App\Actions\Company\UpdateCompanyProfileAction;
use App\Actions\Company\UpdatePromotionAction;
use App\Actions\Company\UploadMediaToS3Action;
use App\Actions\Company\UpdateProductAction;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\StoreProductRequest;
use App\Http\Requests\Company\StorePromotionRequest;
use App\Http\Requests\Company\UpdateCompanyProfileRequest;
use App\Http\Requests\Company\UpdateProductRequest;
use App\Http\Requests\Company\UpdatePromotionRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\PromotionResource;
use App\Services\CompanyRequestService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{

    protected $requestService;

    public function __construct(CompanyRequestService $requestService)
    {
        $this->requestService = $requestService;
    }
    ///function for upload files to aws3
    public function upload(Request $request, UploadMediaToS3Action $uploadAction)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        $path = $uploadAction->execute(
            $request->file('file'),
            'main_pages' // أو خليه ديناميكي
        );

        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'message' => 'File uploaded successfully',
            'path' => $path,
            'url' => $url,
        ]);
    }

    //function to bring the info of first page on company web
    public function getFirstPage(GetGlobalSettingsAction $action): JsonResponse
    {
        try {
            // تنفيذ الـ Action الذي يتضمن الـ Caching في Redis
            $settings = $action->execute();

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Settings retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            // تسجيل الخطأ في Telescope أو Log للمتابعة
            Log::error("Error in InitialConfigController: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإعدادات العامة'
            ], 500);
        }
    }



    //function to return form details
    public function getFormDependencies(): JsonResponse
    {
        $data = $this->requestService->getRegistrationFormData();

        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    ///function to store company request
    public function storeCompanyRequest(StoreCompanyRequest $request, StoreCompanyRequestAction $action): JsonResponse
    {
        try {
            // تنفيذ الأكشن المعتمد على الـ Transaction والحسابات المالية
            $companyRequest = $action->execute($request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'تم استلام طلبكم بنجاح. سيصلكم بريد إلكتروني بتفاصيل الدفع.',
                'data'    => [
                    'id'               => $companyRequest->id,
                    'total_price'      => $companyRequest->total_price,
                    'required_deposit' => $companyRequest->required_deposit,
                    'payment_due'      => $companyRequest->payment_due_date->format('Y-m-d H:i'),
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error("Company Registration Error: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    ///test function
    public function promoteReadyRequests(PromoteApprovedRequestsAction $action)
    {
        $companies = $action->execute();
        return response()->json([
            'status' => 'success',
            'message' => "تمت ترقية {$companies->count()} شركة بنجاح.",
            'data' => $companies
        ]);
    }

    //function to update company profile
    public function updateProfile(UpdateCompanyProfileRequest $request, UpdateCompanyProfileAction $action)
    {
        $company = auth()->user()->company;
        $userData = $request->only(['email', 'phonenumber']);
        if ($request->has('name')) {
            $userData['name'] = $request->name;
        }

        $companyData = $request->only(['bio', 'name']);

        $updatedCompany = $action->execute(
            $company,
            $userData,
            $companyData,
            $request->file('logo')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث بيانات الملف الشخصي بنجاح',
            'data' => $updatedCompany
        ]);
    }

    ////////////////////////Products functions///////////////////////////////
    /// function to store product
    public function storeProduct(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        try {
            $product = $action->execute(
                $request->validated(),
                $request->file('images') ?? [],
                auth()->user()->company
            );

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة المنتج وصوره بنجاح',
                // استخدام الريسورس لمنتج واحد
                'data' => new ProductResource($product->load('images'))
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], in_array($e->getCode(), [403, 401]) ? $e->getCode() : 400);
        }
    }

    /**
     * تحديث منتج موجود
     */
    public function updateProduct(UpdateProductRequest $request, $id, UpdateProductAction $action): JsonResponse
    {
        try {
            $company = auth()->user()->company;
            $product = $company->products()->findOrFail($id);

            $updatedProduct = $action->execute(
                $product,
                $request->validated(),
                $company,
                $request->file('new_images') ?? [],
                $request->input('delete_images') ?? []
            );

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث المنتج بنجاح، جاري معالجة الصور الجديدة.',
                // استخدام الريسورس هنا أيضاً
                'data' => new ProductResource($updatedProduct->load('images'))
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], in_array($e->getCode(), [403, 401]) ? $e->getCode() : 400);
        }
    }

    /**
     * جلب كافة منتجات الشركة
     */
    public function getProduct(GetCompanyProductsAction $action): JsonResponse
    {
        $company = auth()->user()->company;

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'ملف الشركة غير موجود.'
            ], 404);
        }

        $products = $action->execute($company);

        return response()->json([
            'status' => 'success',
            // استخدام collection لأننا نرجع قائمة منتجات
            'data' => ProductResource::collection($products->load('images'))
        ]);
    }

    /**
     * حذف منتج
     */
    public function destroyProduct($id, DeleteProductAction $action): JsonResponse
    {
        try {
            $company = auth()->user()->company;
            $product = $company->products()->find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'المنتج غير موجود.'
                ], 404);
            }

            $action->execute($product);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف المنتج وكافة صوره بنجاح.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

////////////////////////////promotion functions////////////////////////////////
/// function to create promotion
    public function storePromotion(StorePromotionRequest $request, StorePromotionAction $action): JsonResponse
    {
        try {
            $company = auth()->user()->company;
            $promotion = $action->execute($request->validated(), $company);
            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء العرض بنجاح وتطبيقه على المنتجات المختارة.',
                'data' => new PromotionResource($promotion->load('products'))            ], 201);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], in_array($e->getCode(), [403, 401, 422]) ? $e->getCode() : 400);
        }
    }
    //function to update promotion
    public function updatePromotion(UpdatePromotionRequest $request, $id, UpdatePromotionAction $action): JsonResponse
    {
        try {
            $promotion = auth()->user()->company->promotions()->findOrFail($id);

            // تنفيذ التحديث بناءً على الحقول المرسلة فقط
            $updatedPromotion = $action->execute($promotion, $request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديث العرض بنجاح.',
                'data'    => new PromotionResource($updatedPromotion->load('products'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    //function for delete peomotion
    /**
     * حذف عرض محدد
     */
    public function destroyPromotion($id, DeletePromotionAction $action): JsonResponse
    {
        try {
            $promotion = auth()->user()->company->promotions()->find($id);

            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'العرض غير موجود أو لا تملك صلاحية حذفه.'
                ], 404);
            }
            $action->execute($promotion);

            return response()->json([
                'status'  => 'success',
                'message' => 'تم حذف العرض بنجاح.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء محاولة الحذف: ' . $e->getMessage()
            ], 400);
        }
    }
    //function to get company promotions

    public function getPromotions(GetCompanyPromotionsAction $action): JsonResponse
    {
        try {
            $company = auth()->user()->company;

            if (!$company) {
                return response()->json(['status' => 'error', 'message' => 'ملف الشركة غير موجود.'], 404);
            }

            $promotions = $action->execute($company);

            return response()->json([
                'status' => 'success',
                'data' => PromotionResource::collection($promotions)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    //////////////////////home page for company
    public function getCompanyDashboard(GetCompanyDashboardStatsAction $action): JsonResponse
    {
        try {
            $company = auth()->user()->company;

            if (!$company) {
                return response()->json(['status' => 'error', 'message' => 'ملف الشركة غير موجود.'], 404);
            }

            $stats = $action->execute($company);

            return response()->json([
                'status' => 'success',
                'data'   => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
