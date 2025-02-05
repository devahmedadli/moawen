<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['تطوير المواقع', 'تطوير تطبيقات الجوال', 'تصميم واجهات المستخدم', 'التسويق الرقمي', 'كتابة المحتوى', 'تحسين محركات البحث', 'التصميم الجرافيكي', 'مونتاج الفيديو', 'التعليق الصوتي', 'الترجمة'];
        $users = User::ofType('freelancer')->get();
        $categories = Category::all();
        $paragraph = 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام "هنا يوجد محتوى نصي، هنا يوجد محتوى نصي" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب 

            تستخدم لوريم إيبسوم بشكل إفتراضي كنموذج عن النص، وإذا قمت بإدخال "lorem ipsum" في أي محرك بحث ستظهر العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة، وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها.';

        $serviceImageKeywords = [
            'website',
            'mobile-app',
            'ui-design',
            'marketing',
            'writing',
            'seo',
            'design',
            'video',
            'audio',
            'translate'
        ];

        return [
            'user_id'       => $users->random()->id,
            'category_id'   => $categories->random()->id,
            'name'          => fake('ar_EG')->randomElement($names),
            'description'   => $paragraph,
            'service_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'response_time' => fake()->randomElement(['01-20 دقيقة', '1-2 ساعة', '3-4 ساعات']),
            'tags'          => json_encode(fake()->words(3, true)),
            'price'         => fake()->numberBetween(100, 1000),
            'delivery_time' => fake()->randomElement(['3 أيام - 10 أيام', '2 أيام - 3 أيام', '3 أيام - 4 أيام']),
            'thumbnail'     => 'https://placehold.co/640x480/png?text=' . fake()->randomElement($serviceImageKeywords),
            'status'        => fake()->randomElement(['pending', 'revision', 'approved', 'rejected', 'published', 'unpublished']),
            'average_rating' => fake()->randomElement([1, 2, 3.5, 4.7, 5, 2.5]),
            'views'         => fake()->numberBetween(0, 1000),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
