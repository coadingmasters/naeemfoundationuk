<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Handwoven Prayer Mat', 'category' => 'Home & Living', 'price' => 24.99, 'image' => 'images/zakatcenter.png', 'badge' => 'Bestseller',
             'description' => "A beautifully handwoven prayer mat with a soft, cushioned base for comfort during every salah. Durable, lightweight and easy to fold and carry."],
            ['name' => 'The Holy Quran — Gift Edition', 'category' => 'Books', 'price' => 29.99, 'image' => 'images/givezakat.png', 'badge' => null,
             'description' => "A premium hardback Quran with clear Uthmani script, gold-embossed cover and a ribbon marker — a timeless gift for any occasion."],
            ['name' => 'Signature Attar Perfume Set', 'category' => 'Gifts', 'price' => 19.50, 'image' => 'images/supporton.png', 'badge' => 'New',
             'description' => "A set of three long-lasting, alcohol-free attar perfumes in an elegant gift box. Warm, floral and musky notes to suit every taste."],
            ['name' => 'Ramadan Essentials Gift Box', 'category' => 'Ramadan', 'price' => 39.99, 'image' => 'images/givesadqa.jpg', 'badge' => 'Sale',
             'description' => "Everything to welcome the blessed month — dates, a tasbeeh, an attar roll-on, a dua booklet and a Ramadan planner, beautifully boxed."],
            ['name' => 'Cotton Kufi Prayer Cap', 'category' => 'Clothing', 'price' => 7.99, 'image' => 'images/changinslives4.jpg', 'badge' => null,
             'description' => "A breathable, finely-knitted cotton kufi cap. Comfortable for daily wear and available in a classic, understated design."],
            ['name' => 'Crystal Tasbeeh (99 Beads)', 'category' => 'Gifts', 'price' => 6.50, 'image' => 'images/zakathero.png', 'badge' => null,
             'description' => "A hand-strung 99-bead tasbeeh with smooth crystal beads and a tassel — perfect for dhikr on the go."],
            ['name' => 'Ayat al-Kursi Wall Art', 'category' => 'Home & Living', 'price' => 34.00, 'image' => 'images/homepagehero.png', 'badge' => 'Bestseller',
             'description' => "An elegant framed canvas of Ayat al-Kursi in gold calligraphy — a stunning centrepiece for your living space."],
            ['name' => 'Premium Medjool Dates (1kg)', 'category' => 'Ramadan', 'price' => 14.99, 'image' => 'images/changinslives2.jpg', 'badge' => null,
             'description' => "Large, soft and naturally sweet Medjool dates, carefully selected and packed fresh. A wholesome treat for iftar and gifting."],
            ['name' => 'Everyday Abaya — Classic Black', 'category' => 'Clothing', 'price' => 44.99, 'image' => 'images/changinslives3.jpg', 'badge' => 'New',
             'description' => "A flowing, breathable everyday abaya in classic black with subtle detailing. Modest, elegant and comfortable for all-day wear."],
            ["name" => "Children's Islamic Story Books (Set of 5)", 'category' => 'Books', 'price' => 21.99, 'image' => 'images/changinslives1.jpg', 'badge' => null,
             'description' => "A colourful set of five story books teaching young readers about the prophets, good manners and the pillars of Islam."],
            ['name' => 'Luxury Eid Gift Hamper', 'category' => 'Gifts', 'price' => 49.99, 'image' => 'images/voluntear.png', 'badge' => 'Sale',
             'description' => "Celebrate Eid in style — chocolates, dates, attar, a scented candle and a keepsake card, wrapped in a beautiful presentation box."],
            ['name' => 'Natural Miswak Sticks (Pack of 5)', 'category' => 'Home & Living', 'price' => 4.49, 'image' => 'images/handpump.jpg', 'badge' => null,
             'description' => "Traditional miswak sticks from the arak tree — a natural, sunnah way to care for your teeth. Individually sealed for freshness."],
            ['name' => 'Naeem Foundation Charity Tee', 'category' => 'Clothing', 'price' => 16.00, 'image' => 'images/latestnews.png', 'badge' => null,
             'description' => "A soft, ethically-made organic cotton t-shirt. Every purchase supports our work — wear it with pride and spread the word."],
        ];

        foreach ($products as $i => $p) {
            Product::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($p['name'])],
                array_merge($p, ['sort_order' => $i, 'in_stock' => true, 'is_active' => true]),
            );
        }
    }
}
