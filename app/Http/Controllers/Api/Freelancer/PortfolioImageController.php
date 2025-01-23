<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortfolioImage;
use Illuminate\Support\Facades\Storage;

class PortfolioImageController extends Controller
{

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $portfolioImage = PortfolioImage::findOrFail($id);
            Storage::disk('public')->delete($portfolioImage->image);
            $portfolioImage->delete();
            return $this->success(null, 'تم حذف الصورة بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ ما');
        }
    }
}
