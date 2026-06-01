<?php

$filepath = 'e:\\luckydrawsystem\\resources\\views\\welcome.blade.php';
$content = file_get_contents($filepath);

$replacements = [
    '<title>LuckyDraw Pro - Premium Lottery & Draw System</title>' => '<title>LuckoMart - Premium E-commerce Destination</title>',
    'Lucky<span>Draw</span> Pro' => 'Lucko<span>Mart</span>',
    '<a href="#draw">Draw Results</a>' => '<a href="#draw">Flash Sales</a>',
    'Trusted Digital Draw Platform' => 'Trusted E-commerce Platform',
    "Buy Digital Products,<br>\n                    <span>Win Amazing Prizes</span>" => "Discover Premium Products,<br>\n                    <span>Shop with Confidence</span>",
    "Purchase quality digital products and automatically receive entries into our transparent, provably\n                    fair promotional draws. Every purchase counts." => "Your premier destination for high-quality products and seamless shopping experiences. Explore our collection and find exactly what you need.",
    '🏆 $50,000 Grand Prize' => '🔥 Flash Sale Live',
    '<span class="card-title">Live Draws</span>' => '<span class="card-title">Hot Deals</span>',
    'Mega Jackpot #402' => 'Premium Watch Collection',
    'Prize: $50,000.00' => 'Price: $150.00',
    'Emerald Draw' => 'Wireless Headphones',
    'Prize: $2,500.00' => 'Price: $89.00',
    'Total Entries' => 'Total Views',
    'Participants' => 'Active Shoppers',
    '🎉 New winner every day!' => '🎉 New deals every day!',
    '$2.4M+' => '2M+',
    'Prizes Awarded' => 'Products Delivered',
    'Verifiable Fairness' => 'Customer Satisfaction',
    'Three simple steps to start winning' => 'Three simple steps to start shopping',
    'Buy Product' => 'Browse Products',
    'Purchase any digital product from our verified collection and unlock your draw entries instantly.' => 'Explore our wide collection of high-quality products and find exactly what you are looking for.',
    'Get Entry' => 'Secure Checkout',
    'Automatically receive draw entries based on your purchase. More purchases mean more chances to win.' => 'Add items to your cart and proceed to our secure checkout process for a seamless experience.',
    'Transparent Draw' => 'Fast Delivery',
    'Win prizes through our provably fair draw system with full cryptographic transparency and verification.' => 'Receive your products quickly with our reliable and fast delivery network.',
    '● Active Draw' => '● Limited Time Offer',
    'Current Promotional Draw' => 'Current Flash Sale',
    'Draw #2461 · Grand Prize: ৳50,000' => 'Flash Sale #2461 · Up to 50% Off',
    'Prize Pool' => 'Stock Available',
    '৳124,530' => '1,530',
    'Buy Products to Enter' => 'Shop the Sale',
    'Shop & Win' => 'Top Picks',
    'Quality digital products with automatic entries' => 'Handpicked quality products just for you',
    '<span class="product-entries">5 entries</span>' => '<span class="product-entries">In Stock</span>',
    '<span class="product-entries">10 entries</span>' => '<span class="product-entries">10% Off</span>',
    '<span class="product-entries">15 entries</span>' => '<span class="product-entries">15% Off</span>',
    '<span class="product-entries">3 entries</span>' => '<span class="product-entries">Low Stock</span>',
    'No Manipulation' => 'Why Choose Us',
    'Provably Fair System' => 'Secure & Reliable Shopping',
    'Complete transparency in every draw' => 'We ensure the best experience for our customers',
    'Cryptographic Hashing' => 'Secure Payments',
    "Every draw uses server seed hashing that's published before the draw, ensuring no manipulation." => 'Your payment information is encrypted and securely processed.',
    'Immutable Records' => 'Quality Guarantee',
    'All draw results are permanently recorded and can be independently verified by anyone.' => 'We guarantee the quality of our products and offer hassle-free returns.',
    'Public Verification' => 'Fast Shipping',
    'Anyone can verify the fairness of any draw using our open verification tools.' => 'Enjoy fast and reliable shipping on all your orders.',
    '<div class="fair-card-title">Server Seed Hash (Pre-Published)</div>' => '<div class="fair-card-title">Secure Payment Gateway</div>',
    '<div class="fair-card-hash">a1f3d2e8c9b4a7e6d5c2b1a0f9e8d7c6b5a4e3d2c1b0a9f8e7d6c5b4a3e2d1c0b9</div>' => '<div class="fair-card-hash">Verified by Visa, MasterCard, and SSL secure.</div>',
    '<div class="fair-card-title">Client Seed (User-Generated)</div>' => '<div class="fair-card-title">Buyer Protection</div>',
    '<div class="fair-card-hash">f3e4a2d1c0b9a8f7e6d5c4b3a2f1e0d9c8b7a6e5d4</div>' => '<div class="fair-card-hash">Full refund if you don\'t receive your order.</div>',
    '<div class="fair-card-title">Result Hash</div>' => '<div class="fair-card-title">Customer Support</div>',
    '<div class="fair-card-hash">a2d5e4f195d204f795f29b205e5f49a8a2d5b4e194c3a2f1e0d9c8b7a6e5d4c3b2a1</div>' => '<div class="fair-card-hash">24/7 dedicated support team ready to help you.</div>',
    'View Sample Verification' => 'Learn More',
    '"I love the transparency! I can actually verify that the draws are fair. Got great products too."' => '"I love the quality! I can actually rely on this store. Got great products too."',
    '"Finally a platform I can trust. The products are useful and the draws are completely transparent."' => '"Finally a platform I can trust. The products are useful and the shipping is incredibly fast."',
    '"Won my first prize last month! The verification system is amazing. Everything is clear and fair."' => '"Got my first order last month! The packaging is amazing. Everything is clear and fast."',
    'LuckyDraw Pro' => 'LuckoMart',
    'A trusted digital product platform with transparent promotional draws. Every draw is provably fair.' => 'Your premier destination for high-quality products and seamless shopping experiences.',
    'Fair Play Policy' => 'Shipping Policy',
    'support@luckydrawpro.com' => 'support@luckomart.com',
    'This is not a gambling platform.' => 'Shop with confidence.',
    'draw_target' => 'sale_target',
    'drawTarget' => 'saleTarget'
];

foreach ($replacements as $old => $new) {
    $content = str_replace($old, $new, $content);
}

file_put_contents($filepath, $content);
echo "Replacements done.\n";
