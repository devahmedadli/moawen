<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PortfolioResource;
use App\Http\Requests\Api\Freelancer\StorePortfolioRequest;
use App\Http\Requests\Api\Freelancer\UpdatePortfolioRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class PortfolioController extends Controller
{

    // use HasQueryBuilder;

    protected array $allowedRelationships = [
        'images',
    ];


    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Portfolio::where('user_id', auth()->user()->id);
            $query = $this->buildQuery($request, $query);
            $portfolios = $query->get();
            return $this->success(PortfolioResource::collection($portfolios), 'تم جلب معرض الاعمال بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioRequest $request)
    {
        try {
            // begin transaction
            DB::beginTransaction();

            $portfolio = Portfolio::create($request->validated());

            // upload images
            if ($request->has('images')) {
                foreach ($request->images as $image) {
                    $portfolio->images()->create([
                        'path'          => $image->store('portfolio-images', 'public'),
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type'     => $image->getClientMimeType(),
                        'size'          => $image->getSize(),
                        'order'         => $portfolio->images()->count() + 1,
                        'is_primary'    => $portfolio->images()->count() == 0, // true if first file
                    ]);
                }
            }

            // commit transaction
            DB::commit();

            return $this->success(new PortfolioResource($portfolio), 'تم إنشاء معرض الاعمال بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Portfolio $portfolio)
    {
        try {
            $query = Portfolio::where('user_id', auth()->user()->id);
            $query = $this->buildQuery($request, $query);
            $portfolio = $query->first();
            return $this->success(new PortfolioResource($portfolio), 'تم جلب معرض الاعمال بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        try {

            if (!$portfolio) {
                return $this->error('المعرض غير موجود', 404);
            }

            if ($portfolio->user_id !== auth()->user()->id) {
                return $this->error('ليس لديك الصلاحية لتعديل هذا المعرض', 403);
            }

            // begin transaction
            DB::beginTransaction();

            $portfolio->update($request->validated());

            // Delete old images if new images are provided
            if ($request->has('images')) {
                // Upload new images
                foreach ($request->images as $image) {
                    $portfolio->images()->create([
                        'path'          => $image->store('portfolio-images', 'public'),
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type'     => $image->getClientMimeType(),
                        'size'          => $image->getSize(),
                        'order'         => $portfolio->images()->count() + 1,
                        'is_primary'    => $portfolio->images()->count() == 0, // true if first file
                    ]);
                }
            }

            // commit transaction
            DB::commit();

            return $this->success(new PortfolioResource($portfolio), 'تم تعديل معرض الاعمال بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        try {
            if (!$portfolio) {
                return $this->error('المعرض غير موجود', 404);
            }

            if ($portfolio->user_id !== auth()->user()->id) {
                return $this->error('ليس لديك الصلاحية لحذف هذا المعرض', 403);
            }

            $portfolio->delete();
            return $this->success(new PortfolioResource($portfolio), 'تم حذف معرض الاعمال بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
