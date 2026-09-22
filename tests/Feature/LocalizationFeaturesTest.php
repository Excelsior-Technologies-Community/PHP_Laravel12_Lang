<?php

namespace Tests\Feature;

use App\Models\LocalizedProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class LocalizationFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_browser_locale_auto_detection()
    {
        $response = $this->withHeaders([
            'Accept-Language' => 'gu-IN,gu;q=0.9,en;q=0.8',
        ])->get('/');

        $response->assertStatus(200);
        $this->assertEquals('gu', session('locale'));
    }

    public function test_translation_editor_page_is_accessible()
    {
        $response = $this->get('/localization/editor');

        $response->assertStatus(200);
        $response->assertSee('Dynamic Web Translation Key Editor');
    }

    public function test_translation_key_saving()
    {
        $response = $this->post('/localization/editor/save', [
            'translations' => [
                'gu' => [
                    'Test Feature Key' => 'ટેસ્ટ ફીચર કી ટેક્સ્ટ',
                ],
            ],
        ]);

        $response->assertRedirect('/localization/editor');
        $response->assertSessionHas('success');

        $guJson = File::get(base_path('lang/gu.json'));
        $this->assertStringContainsString('ટેસ્ટ ફીચર કી ટેક્સ્ટ', $guJson);
    }

    public function test_product_detail_page_has_tts_audio_reader()
    {
        $product = LocalizedProduct::create([
            'slug' => 'test-audio-wireless-headphones',
            'name_translations' => [
                'en' => 'Test Audio Wireless Headphones',
                'gu' => 'ટેસ્ટ ઓડિયો હેડફોન્સ',
            ],
            'description_translations' => [
                'en' => 'High quality audio headphones for testing TTS reader.',
                'gu' => 'સાઉન્ડ ટેસ્ટિંગ માટે હેડફોન્સ',
            ],
            'price' => 199.99,
        ]);

        $response = $this->get('/products/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('Multilingual Audio Reader');
        $response->assertSee('speechSynthesis');
    }
}
