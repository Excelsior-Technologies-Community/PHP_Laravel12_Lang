<?php

namespace Database\Seeders;

use App\Models\LocalizedProduct;
use Illuminate\Database\Seeder;

class LocalizedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocalizedProduct::create([
            'slug' => 'laptop',
            'name_translations' => [
                'en' => 'Laptop',
                'hi' => 'लैपटॉप',
                'gu' => 'લેપટોપ',
                'es' => 'Portátil',
                'fr' => 'Ordinateur portable',
            ],
            'description_translations' => [
                'en' => 'A powerful laptop for work and study.',
                'hi' => 'काम और पढ़ाई के लिए एक शक्तिशाली लैपटॉप।',
                'gu' => 'કામ અને અભ્યાસ માટે શક્તિશાળી લેપટોપ.',
                'es' => 'Un portátil potente para trabajo y estudio.',
                'fr' => 'Un ordinateur portable puissant pour le travail et les études.',
            ],
            'price' => 55000,
            'category' => 'Electronics',
            'is_active' => true,
        ]);

        LocalizedProduct::create([
            'slug' => 'smartphone',
            'name_translations' => [
                'en' => 'Smartphone',
                'hi' => 'स्मार्टफोन',
                'gu' => 'સ્માર્ટફોન',
                'es' => 'Teléfono inteligente',
                'fr' => 'Smartphone',
            ],
            'description_translations' => [
                'en' => 'A modern smartphone with advanced features.',
                'hi' => 'उन्नत सुविधाओं वाला आधुनिक स्मार्टफोन।',
                'gu' => 'અદ્યતન સુવિધાઓ સાથેનો આધુનિક સ્માર્ટફોન.',
                'es' => 'Un teléfono inteligente moderno con funciones avanzadas.',
                'fr' => 'Un smartphone moderne avec des fonctionnalités avancées.',
            ],
            'price' => 30000,
            'category' => 'Electronics',
            'is_active' => true,
        ]);

        LocalizedProduct::create([
            'slug' => 'headphones',
            'name_translations' => [
                'en' => 'Wireless Headphones',
                'hi' => 'वायरलेस हेडफ़ोन',
                'gu' => 'વાયરલેસ હેડફોન',
                'es' => 'Auriculares inalámbricos',
                'fr' => 'Écouteurs sans fil',
            ],
            'description_translations' => [
                'en' => 'Comfortable wireless headphones with clear sound.',
                'hi' => 'स्पष्ट ध्वनि वाले आरामदायक वायरलेस हेडफ़ोन।',
                'gu' => 'સ્પષ્ટ અવાજ સાથે આરામદાયક વાયરલેસ હેડફોન.',
                'es' => 'Auriculares inalámbricos cómodos con sonido claro.',
                'fr' => 'Des écouteurs sans fil confortables avec un son clair.',
            ],
            'price' => 2500,
            'category' => 'Accessories',
            'is_active' => true,
        ]);

        LocalizedProduct::create([
            'slug' => 'smart-watch',
            'name_translations' => [
                'en' => 'Smart Watch',
                'hi' => 'स्मार्ट वॉच',
                'gu' => 'સ્માર્ટ વોચ',
                'es' => 'Reloj inteligente',
                'fr' => 'Montre intelligente',
            ],
            'description_translations' => [
                'en' => 'A smart watch for fitness and everyday activities.',
                'hi' => 'फिटनेस और रोज़मर्रा की गतिविधियों के लिए स्मार्ट वॉच।',
                'gu' => 'ફિટનેસ અને દૈનિક પ્રવૃત્તિઓ માટે સ્માર્ટ વોચ.',
                'es' => 'Un reloj inteligente para fitness y actividades diarias.',
                'fr' => 'Une montre intelligente pour le fitness et les activités quotidiennes.',
            ],
            'price' => 4500,
            'category' => 'Wearables',
            'is_active' => true,
        ]);

        LocalizedProduct::create([
            'slug' => 'keyboard',
            'name_translations' => [
                'en' => 'Mechanical Keyboard',
                'hi' => 'मैकेनिकल कीबोर्ड',
                'gu' => 'મિકેનિકલ કીબોર્ડ',
                'es' => 'Teclado mecánico',
                'fr' => 'Clavier mécanique',
            ],
            'description_translations' => [
                'en' => 'A durable mechanical keyboard for productive work.',
                'hi' => 'उत्पादक कार्य के लिए टिकाऊ मैकेनिकल कीबोर्ड।',
                'gu' => 'ઉત્પાદક કાર્ય માટે ટકાઉ મિકેનિકલ કીબોર્ડ.',
                'es' => 'Un teclado mecánico duradero para un trabajo productivo.',
                'fr' => 'Un clavier mécanique durable pour un travail productif.',
            ],
            'price' => 3500,
            'category' => 'Accessories',
            'is_active' => true,
        ]);
    }
}