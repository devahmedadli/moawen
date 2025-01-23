<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {    
        // Define specializations by category
        $specializationsByCategory = [
            'programming' => [
                'مطور واجهات أمامية',
                'مطور خلفية',
                'مطور تطبيقات موبايل',
                'مطور ووردبريس',
                'مطور PHP',
                'مطور جافاسكريبت',
                'مبرمج قواعد البيانات',
                'مطور تطبيقات سطح المكتب',
                'مهندس DevOps',
                'مطور ألعاب'
            ],
            'video-editing' => [
                'محرر فيديوهات',
                'مصور فيديو',
                'منتج فيديو',
                'مصمم موشن جرافيك',
                'صانع أفلام قصيرة',
                'منشئ محتوى فيديو',
                'مونتير أفلام',
                'مصمم مؤثرات بصرية'
            ],
            'writing' => [
                'كاتب محتوى',
                'مدقق لغوي',
                'كاتب تقني',
                'كاتب سيناريو',
                'مترجم محتوى',
                'كاتب مقالات',
                'كاتب أخبار',
                'كاتب إبداعي'
            ],
            'social-media' => [
                'مدير وسائل التواصل',
                'منشئ محتوى اجتماعي',
                'مصمم جرافيك للسوشيال',
                'مخطط استراتيجي للسوشيال',
                'محلل وسائل التواصل',
                'مدير مجتمع',
                'مدير حملات سوشيال'
            ],
            'logo-design' => [
                'مصمم شعارات',
                'مصمم هوية بصرية',
                'مصمم جرافيك',
                'رسام',
                'مصمم تايبوجرافي',
                'مصمم علامات تجارية'
            ],
            'paid-ads' => [
                'مدير حملات إعلانية',
                'مخطط حملات جوجل',
                'مدير إعلانات فيسبوك',
                'محلل إعلانات رقمية',
                'مدير تسويق رقمي',
                'مخطط حملات تيك توك'
            ],
            'analysis' => [
                'محلل بيانات',
                'محلل أعمال',
                'محلل سوق',
                'باحث',
                'محلل استراتيجي',
                'محلل مالي'
            ],
            'e-commerce' => [
                'مدير متجر إلكتروني',
                'مطور متاجر شوبيفاي',
                'مصمم متاجر إلكترونية',
                'مدير منتجات',
                'مدير مبيعات إلكترونية',
                'مخطط تجارة إلكترونية'
            ]
        ];

        // Get a random category
        $category = Category::inRandomOrder()->first();
        
        if (!$category) {
            throw new \RuntimeException('No categories found in the database.');
        }

        // Get specializations for this category's slug
        $specializations = $specializationsByCategory[$category->slug] ?? ['متخصص عام'];
        
        return [
            'name' => fake()->randomElement($specializations),
            'category_id' => $category->id,
        ];
    }
}
