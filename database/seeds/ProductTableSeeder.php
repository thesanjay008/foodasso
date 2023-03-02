<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$data = [
			['Margherita Pizza', 'A Classic Cheesy Margharita. Can\'t Go Wrong.', 'default/demo/products/product-1.jpg', '110', '1', 'veg'],
			['Garden Delight Pizza', 'A Classic Veg Pizza That Combines The Zing And Freshness Of Onions, Tomatoes And Capsicum', 'default/demo/products/product-2.jpg', '165', '1', 'veg'],
			['Lovers Bite Pizza', 'A Wholesome Combination Of Tossed Mushrooms, Olives And Juicy Sweet Corn', 'default/demo/products/product-3.jpg', '165', '1', 'veg'],
			['Burn To Hell Pizza', 'A Fiery And Lethal Combination Of Hot & Garlic Dip, Jalapenos, Mushrooms, Olives And Capsicum', 'default/demo/products/product-4.jpg', '225', '1', 'veg'],
			['Plain Garlic Bread', '[50 GM] The Quintessential Garlic Bread Side For Your Pizza', 'default/demo/products/product-5.jpg', '79', '2', 'veg'],
			['Cheesy Garlic Bread', '[90 GM] Garlic Bread Baked To Perfection With Cheese', 'default/demo/products/product-6.jpg', '89', '2', 'veg'],
			['Macaroni & Cheese Veg', 'Quintessential Veg Macaroni N Cheese, La Pinoz Style', 'default/demo/products/product-7.jpg', '149', '3', 'veg'],
			['Cheese Butter Masala Combo', 'Cheese Butter Masala + Choice Of 5 Tawa Roti Or 3 Tandoori Roti', 'default/demo/products/product-8.jpg', '289', '4', 'veg'],
			['Cheese Chilli Paratha Meal', 'Cheese Chilli Paratha + Chole + Veg Raita', 'default/demo/products/product-9.jpg', '269', '4', 'veg'],
			['Choco Lava', '[100 GM] Loaded with premium chocolate', 'default/demo/products/product-10.jpg', '99', '5', 'veg'],
			['Red Velvet Lava Cake', '[100 GM] Loaded with Premium Red Velvet Lava', 'default/demo/products/product-11.jpg', '129', '5', 'veg'],
			['Black Forest Mcflurry (M) BOGO', 'Black Forest Mcflurry (M) BOGO', 'default/demo/products/product-12.jpg', '99', '5', 'veg'],
			['(Can) Diet Coke', 'The perfect diet companion to your burger, fries and everything nice. Regular serving size, 300 Ml.', 'default/demo/products/product-13.jpg', '99', '6', 'veg'],
		];
        foreach ($data as $key => $value) {
            $createArray = array();
            
            $createArray['title:en']		= $value[0];
            $createArray['description:en']	= $value[1];
            $createArray['image']			= $value[2];
            $createArray['price']			= $value[3];
            $createArray['menu_category_id'] = $value[4];
            $createArray['choice']			= $value[5];

			Product::create($createArray);
        }
    }
}