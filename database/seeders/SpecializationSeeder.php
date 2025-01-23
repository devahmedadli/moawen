<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
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
            ],
            'video-editing' => [
                'محرر فيديوهات',
                'مصور فيديو',
                'منتج فيديو',
                'مصمم موشن جرافيك',
                'صانع أفلام قصيرة',
                'منشئ محتوى فيديو',
            ],
            'writing' => [
                'كاتب محتوى',
                'مدقق لغوي',
                'كاتب تقني',
                'كاتب سيناريو',
                'مترجم محتوى',
                'كاتب مقالات',
            ],
            'social-media' => [
                'مدير وسائل التواصل',
                'منشئ محتوى اجتماعي',
                'مصمم جرافيك للسوشيال',
                'مخطط استراتيجي للسوشيال',
                'مدير مجتمع',
            ],
            'logo-design' => [
                'مصمم شعارات',
                'مصمم هوية بصرية',
                'مصمم جرافيك',
                'رسام',
                'مصمم تايبوجرافي',
            ],
            'paid-ads' => [
                'مدير حملات إعلانية',
                'مخطط حملات جوجل',
                'مدير إعلانات فيسبوك',
                'محلل إعلانات رقمية',
                'مدير تسويق رقمي',
            ],
            'analysis' => [
                'محلل بيانات',
                'محلل أعمال',
                'محلل سوق',
                'باحث',
                'محلل استراتيجي',
            ],
            'e-commerce' => [
                'مدير متجر إلكتروني',
                'مطور متاجر شوبيفاي',
                'مصمم متاجر إلكترونية',
                'مدير منتجات',
                'مدير مبيعات إلكترونية',
            ]
        ];

        foreach (Category::all() as $category) {
            $specializations = $specializationsByCategory[$category->slug] ?? ['متخصص عام'];
            
            foreach ($specializations as $specialization) {
                Specialization::create([
                    'name' => $specialization,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
