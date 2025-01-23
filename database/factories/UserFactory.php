<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specializations = ['مطور', 'مصمم', 'تسويقي', 'كاتب', 'تحسين', 'تصميم', 'مونتاج', 'تعليق', 'ترجم'];
        $bio = 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام "هنا يوجد محتوى نصي، هنا يوجد محتوى نصي" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب تستخدم لوريم إيبسوم بشكل إفتراضي كنموذج عن النص.';
        $skills = [
            'HTML',
            'CSS',
            'JavaScript',
            'PHP',
            'MySQL',
            'Python',
            'Java',
            'C#',
            'C++',
            'C',
            'Laravel',
            'Vue.js',
            'React.js',
        ];
        return [
            'first_name'        => fake('ar_EG')->firstName(array_rand(['male', 'female'])),
            'last_name'         => fake('ar_EG')->lastName(array_rand(['male', 'female'])),
            'specialization_id'    => fake()->randomElement(Specialization::pluck('id')),
            'username'          => fake()->userName(),
            'email'             => fake('ar_EG')->unique()->safeEmail(),
            'phone'             => fake('ar_EG')->phoneNumber(),
            'birthdate'         => fake('ar_EG')->date(),
            'gender'            => fake()->randomElement(['male', 'female']),
            'years_of_experience' => fake()->numberBetween(1, 10),
            'bio'               => $bio,
            'skills'            => json_encode(fake()->randomElements($skills, fake()->numberBetween(1, 5))),
            'country'           => fake('ar_EG')->country(),
            'image'             => 'https://randomuser.me/api/portraits/' . (fake()->boolean() ? 'men' : 'women') . '/' . fake()->numberBetween(1, 99) . '.jpg',
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'role'              => fake()->randomElement(['freelancer', 'client']),
            'status'            => fake()->randomElement(['active', 'inactive']),
            'is_2fa_enabled'    => fake()->boolean(),
            'kyc_status'        => fake()->randomElement(['pending', 'approved', 'rejected']),
            'account_level'     => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'average_rating'    => fake()->numberBetween(1, 5),
            'last_login_at'     => now(),
            'last_login_ip'     => fake()->ipv4(),
            'created_at'        => now(),
            'updated_at'        => now(),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
