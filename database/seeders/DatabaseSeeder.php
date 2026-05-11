<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $commissionRate = 10.00;

        // 1. ADMIN
        DB::table('users')->insert([
            'name'           => 'TrimTime Admin',
            'email'          => 'trimtime66@gmail.com',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'loyalty_points' => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 2. SHOP OWNERS
        $ownerNames = [
            ['Ramesh Shrestha',   'ramesh@trimtime.com',   '9841000001'],
            ['Sunil Tamang',      'sunil@trimtime.com',    '9841000002'],
            ['Bikash Gurung',     'bikash@trimtime.com',   '9841000003'],
            ['Prakash Oli',       'prakash@trimtime.com',  '9841000004'],
            ['Dipesh Karki',      'dipesh@trimtime.com',   '9841000005'],
            ['Sanjay Rai',        'sanjay@trimtime.com',   '9841000006'],
            ['Naresh Magar',      'naresh@trimtime.com',   '9841000007'],
            ['Bijay Thapa',       'bijay@trimtime.com',    '9841000008'],
            ['Lokesh Basnet',     'lokesh@trimtime.com',   '9841000009'],
            ['Manish Lama',       'manish.l@trimtime.com', '9841000010'],
            ['Sushil Poudel',     'sushil@trimtime.com',   '9841000011'],
            ['Raju Adhikari',     'raju@trimtime.com',     '9841000012'],
            ['Binod Khadka',      'binod@trimtime.com',    '9841000013'],
            ['Gopal Joshi',       'gopal@trimtime.com',    '9841000014'],
            ['Ashok Bajracharya', 'ashok@trimtime.com',    '9841000015'],
            ['Himal Maharjan',    'himal@trimtime.com',    '9841000016'],
            ['Roshan Neupane',    'roshan.n@trimtime.com', '9841000017'],
            ['Dinesh Bhatta',     'dinesh@trimtime.com',   '9841000018'],
            ['Kamal Ghimire',     'kamal@trimtime.com',    '9841000019'],
            ['Prabesh Upreti',    'prabesh@trimtime.com',  '9841000020'],
            ['Nirajan Koirala',   'nirajan@trimtime.com',  '9841000021'],
            ['Santosh Dhakal',    'santosh@trimtime.com',  '9841000022'],
            ['Bibek Acharya',     'bibek.a@trimtime.com',  '9841000023'],
            ['Arun Shrestha',     'arun@trimtime.com',     '9841000024'],
            ['Deepak Maharjan',   'deepak.m@trimtime.com', '9841000025'],
        ];

        $ownerIds = [];
        foreach ($ownerNames as $o) {
            $ownerIds[] = DB::table('users')->insertGetId([
                'name'           => $o[0],
                'email'          => $o[1],
                'password'       => Hash::make('password'),
                'role'           => 'barber_shop',
                'phone'          => $o[2],
                'loyalty_points' => 0,
                'created_at'     => now()->subDays(rand(30, 120)),
                'updated_at'     => now(),
            ]);
        }

        // 3. BARBER SHOPS
        $shopDefs = [
            ['Classic Cuts Kathmandu',   'Thamel, Kathmandu',         'Kathmandu', 'Kathmandu', 27.7172, 85.3240, 4.5, 42, 'approved',  '09:00', '19:00', 90],
            ['Style Zone Lalitpur',      'Kupondole, Lalitpur',       'Lalitpur',  'Lalitpur',  27.6788, 85.3159, 4.2, 28, 'approved',  '10:00', '20:00', 80],
            ['Royal Blade Bhaktapur',    'Durbar Square, Bhaktapur',  'Bhaktapur', 'Bhaktapur', 27.6710, 85.4298, 4.7, 55, 'approved',  '08:00', '18:00', 100],
            ['The Barber House',         'New Baneshwor, Kathmandu',  'Kathmandu', 'Kathmandu', 27.6933, 85.3424, 4.3, 36, 'approved',  '09:00', '20:00', 75],
            ['Fade Factory',             'Baluwatar, Kathmandu',      'Kathmandu', 'Kathmandu', 27.7226, 85.3192, 4.6, 48, 'approved',  '10:00', '21:00', 85],
            ['Gents Gallery',            'Pulchowk, Lalitpur',        'Lalitpur',  'Lalitpur',  27.6803, 85.3180, 4.1, 22, 'approved',  '09:30', '19:30', 60],
            ['Sharp Edge Studio',        'Chabahil, Kathmandu',       'Kathmandu', 'Kathmandu', 27.7208, 85.3569, 4.4, 33, 'approved',  '09:00', '19:00', 70],
            ['Urban Grooming Lounge',    'Lazimpat, Kathmandu',       'Kathmandu', 'Kathmandu', 27.7241, 85.3189, 4.8, 61, 'approved',  '10:00', '20:00', 110],
            ['Prestige Barber Shop',     'Boudha, Kathmandu',         'Kathmandu', 'Kathmandu', 27.7215, 85.3620, 4.0, 18, 'approved',  '08:30', '18:30', 55],
            ['The Cut Lab',              'Maharajgunj, Kathmandu',    'Kathmandu', 'Kathmandu', 27.7368, 85.3240, 4.5, 40, 'approved',  '09:00', '21:00', 65],
            ['Kathmandu Cuts',           'Gongabu, Kathmandu',        'Kathmandu', 'Kathmandu', 27.7404, 85.3175, 4.2, 27, 'approved',  '09:00', '19:00', 50],
            ['The Trim Spot',            'Koteshwor, Kathmandu',      'Kathmandu', 'Kathmandu', 27.6844, 85.3524, 4.3, 31, 'approved',  '09:30', '20:00', 58],
            ['Classic Grooming Co.',     'Patan Dhoka, Lalitpur',     'Lalitpur',  'Lalitpur',  27.6710, 85.3140, 4.6, 44, 'approved',  '08:00', '19:00', 95],
            ['Mens Hub Barber',          'Kirtipur, Kathmandu',       'Kathmandu', 'Kathmandu', 27.6781, 85.2780, 4.1, 19, 'approved',  '10:00', '19:00', 42],
            ['Blade and Style',          'Budhanilkantha, Kathmandu', 'Kathmandu', 'Kathmandu', 27.7737, 85.3618, 4.5, 38, 'approved',  '09:00', '20:00', 68],
            ['The Gentlemens Club',      'Naxal, Kathmandu',          'Kathmandu', 'Kathmandu', 27.7152, 85.3265, 4.7, 52, 'approved',  '10:00', '21:00', 88],
            ['Haircraft Studio',         'Bagbazar, Kathmandu',       'Kathmandu', 'Kathmandu', 27.7054, 85.3155, 4.3, 29, 'approved',  '09:00', '19:30', 72],
            ['Modern Barber Co.',        'Durbarmarg, Kathmandu',     'Kathmandu', 'Kathmandu', 27.7099, 85.3171, 4.4, 35, 'approved',  '10:00', '20:00', 66],
            ['Trim and Groom',           'Imadol, Lalitpur',          'Lalitpur',  'Lalitpur',  27.6608, 85.3350, 4.2, 23, 'approved',  '09:00', '18:30', 45],
            ['Studio 7 Barbershop',      'Sinamangal, Kathmandu',     'Kathmandu', 'Kathmandu', 27.6974, 85.3537, 4.6, 46, 'approved',  '09:30', '20:30', 82],
            ['The Neighbourhood Barber', 'Satdobato, Lalitpur',       'Lalitpur',  'Lalitpur',  27.6578, 85.3279, 4.0, 15, 'approved',  '08:30', '18:00', 38],
            ['Prime Cuts Balaju',        'Balaju, Kathmandu',         'Kathmandu', 'Kathmandu', 27.7420, 85.3072, 4.3, 26, 'approved',  '09:00', '19:00', 53],
            // pending
            ['Fresh Style Pokhara',      'Lakeside, Pokhara',         'Pokhara',   'Kaski',     28.2096, 83.9856, 0.0,  0, 'pending',   '09:00', '18:00', 5],
            ['Grooming Hub Chitwan',     'Bharatpur-10, Chitwan',     'Bharatpur', 'Chitwan',   27.6833, 84.4333, 0.0,  0, 'pending',   '10:00', '19:00', 2],
            // suspended
            ['Quick Cuts Jorpati',       'Jorpati, Kathmandu',        'Kathmandu', 'Kathmandu', 27.7433, 85.3882, 3.2,  8, 'suspended', '09:00', '18:00', 60],
        ];

        $shopIds = [];
        foreach ($shopDefs as $i => $s) {
            $shopIds[] = DB::table('barber_shops')->insertGetId([
                'name'              => $s[0],
                'address'           => $s[1],
                'city'              => $s[2],
                'district'          => $s[3],
                'latitude'          => $s[4],
                'longitude'         => $s[5],
                'average_rating'    => $s[6],
                'total_reviews'     => $s[7],
                'status'            => $s[8],
                'opening_time'      => $s[9] . ':00',
                'closing_time'      => $s[10] . ':00',
                'number_of_barbers' => rand(2, 3),
                'number_of_chairs'  => rand(2, 3),
                'owner_name'        => $ownerNames[$i][0],
                'phone'             => '01-' . rand(1000000, 9999999),
                'email'             => 'shop' . ($i + 1) . '@trimtime.com',
                'pan_number'        => 'PAN' . rand(100000, 999999),
                'description'       => 'Professional barber shop offering quality haircuts and grooming in ' . $s[2] . '.',
                'reviewed_at'       => $s[8] === 'approved' ? now()->subDays(rand(5, 30)) : null,
                'created_at'        => now()->subDays($s[11]),
                'updated_at'        => now(),
            ]);
        }

        foreach ($ownerIds as $i => $oid) {
            DB::table('users')->where('id', $oid)->update(['barber_shop_id' => $shopIds[$i]]);
        }

        // 4. BARBERS (2-3 per approved shop)
        $barberNames = [
            'Anil Rai', 'Deepak Karki', 'Sagar Magar', 'Roshan Thapa', 'Nabin Lama',
            'Subash Shrestha', 'Prem Tamang', 'Hari Gurung', 'Kiran Rai', 'Santosh Thapa',
            'Arjun Adhikari', 'Suman Lama', 'Binay Magar', 'Rajesh Khadka', 'Gaurav Poudel',
            'Niraj Basnet', 'Ravi Maharjan', 'Dinesh Shrestha', 'Prakash Rai', 'Amit Tamang',
            'Bikram Gurung', 'Suresh Thapa', 'Mohan Karki', 'Laxman Magar', 'Dilip Rai',
            'Prasad Adhikari', 'Bishnu Shrestha', 'Ramesh Lama', 'Krishna Khadka', 'Manoj Basnet',
            'Sudip Maharjan', 'Bikash Thapa', 'Pawan Shrestha', 'Rajan Tamang', 'Anup Gurung',
            'Sanjay Karki', 'Pradeep Rai', 'Bhim Magar', 'Aman Thapa', 'Rohit Adhikari',
            'Nishan Lama', 'Kapil Shrestha', 'Sachin Khadka', 'Ajay Basnet', 'Tilak Maharjan',
            'Purna Rai', 'Diwas Tamang', 'Narayan Gurung', 'Bidur Thapa', 'Saurav Karki',
            'Ujwal Magar', 'Sushant Lama', 'Prabin Rai', 'Sujan Thapa', 'Bibek Tamang',
        ];

        $barberIds   = [];
        $shopBarbers = [];
        $nameIdx     = 0;

        // only approved shops (first 22)
        foreach (array_slice($shopIds, 0, 22) as $shopId) {
            $shopBarbers[$shopId] = [];
            $count = rand(2, 3);
            for ($b = 0; $b < $count; $b++) {
                $bName = $barberNames[$nameIdx % count($barberNames)];
                $nameIdx++;
                $bid = DB::table('barbers')->insertGetId([
                    'barber_shop_id'   => $shopId,
                    'name'             => $bName,
                    'phone'            => '980' . rand(1000000, 9999999),
                    'email'            => strtolower(str_replace(' ', '.', $bName)) . rand(1, 99) . '@trimtime.com',
                    'experience_years' => rand(1, 8),
                    'bio'              => 'Professional barber with expertise in modern and classic styles.',
                    'status'           => 'active',
                    'average_rating'   => round(rand(38, 50) / 10, 1),
                    'total_reviews'    => rand(5, 30),
                    'created_at'       => now()->subDays(rand(20, 100)),
                    'updated_at'       => now(),
                ]);
                $barberIds[]            = $bid;
                $shopBarbers[$shopId][] = $bid;
            }
        }

        // 5. SERVICES
        $catHaircut    = DB::table('service_categories')->where('slug', 'haircut')->value('id');
        $catBeard      = DB::table('service_categories')->where('slug', 'beard')->value('id');
        $catHairStyle  = DB::table('service_categories')->where('slug', 'hair-style')->value('id');
        $catHairColour = DB::table('service_categories')->where('slug', 'hair-colour')->value('id');
        $catFacial     = DB::table('service_categories')->where('slug', 'facial-skin')->value('id');
        $catTreatment  = DB::table('service_categories')->where('slug', 'hair-treatment')->value('id');

        $svcTemplates = [
            ['Regular Haircut',     $catHaircut,    200,  30],
            ['Fade Cut',            $catHaircut,    350,  45],
            ['Kids Haircut',        $catHaircut,    150,  20],
            ['Classic Cut',         $catHaircut,    250,  30],
            ['Beard Trim',          $catBeard,      150,  20],
            ['Beard Shaping',       $catBeard,      200,  25],
            ['Full Beard Grooming', $catBeard,      300,  30],
            ['Hair Styling',        $catHairStyle,  400,  40],
            ['Hair Colour',         $catHairColour, 800,  60],
            ['Highlights',          $catHairColour, 1200, 90],
            ['Facial',              $catFacial,     500,  45],
            ['Hair Treatment',      $catTreatment,  600,  60],
        ];

        $shopServices = [];
        foreach (array_slice($shopIds, 0, 22) as $shopId) {
            $shopServices[$shopId] = [];
            $pool = $svcTemplates;
            shuffle($pool);
            foreach (array_slice($pool, 0, rand(5, 7)) as $svc) {
                $sid = DB::table('services')->insertGetId([
                    'barber_shop_id' => $shopId,
                    'category_id'    => $svc[1],
                    'name'           => $svc[0],
                    'price'          => $svc[2],
                    'duration'       => $svc[3],
                    'status'         => 'active',
                    'created_at'     => now()->subDays(rand(10, 80)),
                    'updated_at'     => now(),
                ]);
                $shopServices[$shopId][] = ['id' => $sid, 'price' => $svc[2]];
            }
        }

        // 6. CUSTOMERS (55)
        $custData = [
            ['Aarav Sharma',     'aarav.sharma',     '9860000001', 120],
            ['Priya Koirala',    'priya.koirala',    '9860000002', 80],
            ['Bibek Adhikari',   'bibek.adhikari',   '9860000003', 200],
            ['Sita Rai',         'sita.rai',         '9860000004', 50],
            ['Manish Poudel',    'manish.poudel',    '9860000005', 160],
            ['Anita Basnet',     'anita.basnet',     '9860000006', 30],
            ['Rajan Khadka',     'rajan.khadka',     '9860000007', 90],
            ['Sunita Joshi',     'sunita.joshi',     '9860000008', 0],
            ['Aakash Thapa',     'aakash.thapa',     '9860000009', 140],
            ['Nisha Tamang',     'nisha.tamang',     '9860000010', 60],
            ['Rohan Gurung',     'rohan.gurung',     '9860000011', 110],
            ['Pooja Shrestha',   'pooja.shrestha',   '9860000012', 45],
            ['Saurav Karki',     'saurav.karki',     '9860000013', 180],
            ['Rekha Magar',      'rekha.magar',      '9860000014', 20],
            ['Nabin Lama',       'nabin.lama2',      '9860000015', 75],
            ['Kabita Rai',       'kabita.rai',       '9860000016', 95],
            ['Prakash Maharjan', 'prakash.maharjan', '9860000017', 130],
            ['Shristi Bhatta',   'shristi.bhatta',   '9860000018', 55],
            ['Dipak Ghimire',    'dipak.ghimire',    '9860000019', 210],
            ['Anjali Upreti',    'anjali.upreti',    '9860000020', 40],
            ['Sujan Koirala',    'sujan.koirala',    '9860000021', 85],
            ['Binita Dhakal',    'binita.dhakal',    '9860000022', 15],
            ['Rajesh Acharya',   'rajesh.acharya',   '9860000023', 170],
            ['Mina Shrestha',    'mina.shrestha',    '9860000024', 65],
            ['Santosh Paudel',   'santosh.paudel',   '9860000025', 100],
            ['Laxmi Thapa',      'laxmi.thapa',      '9860000026', 35],
            ['Niraj Tamang',     'niraj.tamang',     '9860000027', 155],
            ['Puja Gurung',      'puja.gurung',      '9860000028', 70],
            ['Arun Karki',       'arun.karki',       '9860000029', 195],
            ['Sabina Magar',     'sabina.magar',     '9860000030', 25],
            ['Dilip Rai',        'dilip.rai',        '9860000031', 115],
            ['Kamala Basnet',    'kamala.basnet',     '9860000032', 50],
            ['Suresh Joshi',     'suresh.joshi',     '9860000033', 140],
            ['Ritu Khadka',      'ritu.khadka',      '9860000034', 80],
            ['Prabin Thapa',     'prabin.thapa',     '9860000035', 220],
            ['Gita Tamang',      'gita.tamang',      '9860000036', 45],
            ['Bikash Gurung',    'bikash.gurung2',   '9860000037', 175],
            ['Srijana Lama',     'srijana.lama',     '9860000038', 60],
            ['Kushal Rai',       'kushal.rai',       '9860000039', 125],
            ['Pramila Adhikari', 'pramila.adhikari', '9860000040', 30],
            ['Sabin Shrestha',   'sabin.shrestha',   '9860000041', 90],
            ['Anjana Maharjan',  'anjana.maharjan',  '9860000042', 185],
            ['Rosan Poudel',     'rosan.poudel',     '9860000043', 55],
            ['Sweta Dhakal',     'sweta.dhakal',     '9860000044', 105],
            ['Biraj Ghimire',    'biraj.ghimire',    '9860000045', 150],
            ['Sushmita Upreti',  'sushmita.upreti',  '9860000046', 70],
            ['Ganesh Koirala',   'ganesh.koirala',   '9860000047', 200],
            ['Sushma Acharya',   'sushma.acharya',   '9860000048', 35],
            ['Naresh Shrestha',  'naresh.shrestha',  '9860000049', 165],
            ['Pabitra Thapa',    'pabitra.thapa',    '9860000050', 85],
            ['Ujjwal Tamang',    'ujjwal.tamang',    '9860000051', 120],
            ['Sabita Gurung',    'sabita.gurung',    '9860000052', 45],
            ['Dipesh Karki',     'dipesh.karki2',    '9860000053', 190],
            ['Menuka Rai',       'menuka.rai',       '9860000054', 70],
            ['Suman Basnet',     'suman.basnet',     '9860000055', 110],
        ];

        $customerIds = [];
        foreach ($custData as $c) {
            $customerIds[] = DB::table('users')->insertGetId([
                'name'           => $c[0],
                'email'          => $c[1] . '@gmail.com',
                'password'       => Hash::make('password'),
                'role'           => 'user',
                'phone'          => $c[2],
                'loyalty_points' => $c[3],
                'created_at'     => now()->subDays(rand(5, 90)),
                'updated_at'     => now(),
            ]);
        }

        // 7. BOOKINGS
        // Most bookings are completed+paid to show big revenue numbers
        // Also includes: cancelled with fine paid, cancelled fine unpaid, pending, confirmed, no_show
        $approvedShops = array_slice($shopIds, 0, 22);

        $reviewComments = [
            'Excellent service! Very professional and clean.',
            'Great haircut. Will definitely come back.',
            'The barber was very skilled and friendly.',
            'Very satisfied with the result. Highly recommend.',
            'Clean shop, good service. Worth the price.',
            'Quick and precise cut. No waiting time.',
            'Amazing beard grooming. Looks fantastic!',
            'Best haircut I have had in a long time.',
            'Friendly staff and great atmosphere.',
            'Good value for money. Nice experience.',
            'The fade cut was done perfectly.',
            'Professional service and clean environment.',
            'Very happy with the result. Will return.',
            'Skilled barber, really knows his craft.',
            'Loved the experience. Highly recommended!',
            'Nice and comfortable shop. Good service.',
            'The hair colour came out exactly as I wanted.',
            'Excellent facial treatment. Felt refreshed.',
            'Great hair treatment. My hair looks amazing.',
            'Quick service and good quality cut.',
        ];

        $bookingIds  = [];
        $bookingMeta = [];

        // generate 300 bookings spread over last 90 days
        for ($i = 0; $i < 300; $i++) {
            $shopId   = $approvedShops[array_rand($approvedShops)];
            $barbers  = $shopBarbers[$shopId] ?? [];
            $services = $shopServices[$shopId] ?? [];

            if (empty($barbers) || empty($services)) {
                continue;
            }

            $barberId = $barbers[array_rand($barbers)];
            $svcItem  = $services[array_rand($services)];
            $userId   = $customerIds[array_rand($customerIds)];
            $price    = $svcItem['price'];

            // booking type distribution:
            // 65% completed+paid, 10% cancelled with fine paid, 5% cancelled fine unpaid,
            // 8% confirmed upcoming, 7% pending, 5% no_show
            $rand = rand(1, 100);
            if ($rand <= 65) {
                $status     = 'completed';
                $payStatus  = 'paid';
                $finePaid   = false;
                $fine       = 0;
                $dayOffset  = -rand(1, 85);
            } elseif ($rand <= 75) {
                // cancelled with fine already paid
                $status     = 'cancelled';
                $payStatus  = 'unpaid';
                $fine       = 100;
                $finePaid   = true;
                $dayOffset  = -rand(1, 60);
            } elseif ($rand <= 80) {
                // cancelled fine not yet paid
                $status     = 'cancelled';
                $payStatus  = 'unpaid';
                $fine       = 100;
                $finePaid   = false;
                $dayOffset  = -rand(1, 30);
            } elseif ($rand <= 88) {
                // confirmed upcoming
                $status     = 'confirmed';
                $payStatus  = 'unpaid';
                $fine       = 0;
                $finePaid   = false;
                $dayOffset  = rand(1, 14);
            } elseif ($rand <= 95) {
                // pending upcoming
                $status     = 'pending';
                $payStatus  = 'unpaid';
                $fine       = 0;
                $finePaid   = false;
                $dayOffset  = rand(1, 14);
            } else {
                // no_show
                $status     = 'no_show';
                $payStatus  = 'unpaid';
                $fine       = 0;
                $finePaid   = false;
                $dayOffset  = -rand(1, 30);
            }

            $payMethod  = rand(0, 1) ? 'khalti' : 'cod';
            $commission = round($price * ($commissionRate / 100), 2);
            $earnings   = round($price - $commission, 2);
            $advance    = 0;
            $remaining  = 0;
            $date       = Carbon::today()->addDays($dayOffset);

            $bid = DB::table('bookings')->insertGetId([
                'user_id'                => $userId,
                'barber_shop_id'         => $shopId,
                'barber_id'              => $barberId,
                'service_id'             => $svcItem['id'],
                'booking_date'           => $date->toDateString(),
                'start_time'             => '10:00:00',
                'end_time'               => '10:30:00',
                'total_price'            => $price,
                'total_duration_minutes' => 30,
                'final_price'            => $price,
                'status'                 => $status,
                'payment_status'         => $payStatus,
                'payment_method'         => $payMethod,
                'original_amount'        => $price,
                'discount_amount'        => 0,
                'final_amount'           => $price,
                'redeemed_points'        => 0,
                'advance_amount'         => $advance,
                'remaining_amount'       => $remaining,
                'cancellation_fine'      => $fine,
                'fine_paid'              => $finePaid,
                'reminder_sent'          => false,
                'commission_amount'      => $status === 'completed' ? $commission : 0,
                'commission_rate'        => $status === 'completed' ? $commissionRate : 0,
                'shop_earnings'          => $status === 'completed' ? $earnings : 0,
                'created_at'             => $date->copy()->subHours(rand(2, 48)),
                'updated_at'             => now(),
            ]);

            $bookingIds[]  = $bid;
            $bookingMeta[] = [
                'id'         => $bid,
                'user_id'    => $userId,
                'shop_id'    => $shopId,
                'barber_id'  => $barberId,
                'status'     => $status,
                'pay_method' => $payMethod,
                'pay_status' => $payStatus,
                'price'      => $price,
                'fine'       => $fine,
                'fine_paid'  => $finePaid,
                'day_offset' => $dayOffset,
                'commission' => $commission,
                'earnings'   => $earnings,
            ];
        }

        // 8. PAYMENTS
        foreach ($bookingMeta as $m) {
            // payment for completed+paid bookings
            if ($m['status'] === 'completed') {
                DB::table('payments')->insert([
                    'booking_id'            => $m['id'],
                    'user_id'               => $m['user_id'],
                    'amount'                => $m['price'],
                    'payment_type'          => 'full',
                    'payment_for'           => 'booking',
                    'payment_method'        => $m['pay_method'],
                    'khalti_pidx'           => $m['pay_method'] === 'khalti' ? 'pidx_' . strtoupper(substr(md5($m['id'] . 'p'), 0, 12)) : null,
                    'khalti_transaction_id' => $m['pay_method'] === 'khalti' ? 'txn_' . strtoupper(substr(md5($m['id'] . 't'), 0, 12)) : null,
                    'status'                => 'completed',
                    'paid_at'               => Carbon::today()->addDays($m['day_offset']),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }

            // payment for cancelled fine that was paid
            if ($m['status'] === 'cancelled' && $m['fine'] > 0 && $m['fine_paid']) {
                DB::table('payments')->insert([
                    'booking_id'            => $m['id'],
                    'user_id'               => $m['user_id'],
                    'amount'                => $m['fine'],
                    'payment_type'          => 'fine',
                    'payment_for'           => 'fine',
                    'payment_method'        => 'khalti',
                    'khalti_pidx'           => 'pidx_fine_' . strtoupper(substr(md5($m['id'] . 'f'), 0, 8)),
                    'khalti_transaction_id' => 'txn_fine_' . strtoupper(substr(md5($m['id'] . 'ft'), 0, 8)),
                    'status'                => 'completed',
                    'paid_at'               => Carbon::today()->addDays($m['day_offset'])->addHours(2),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }
        }

        // 9. REVIEWS for completed bookings (~70% get a review)
        foreach ($bookingMeta as $m) {
            if ($m['status'] !== 'completed') {
                continue;
            }
            if (rand(1, 10) > 7) {
                continue;
            }
            DB::table('reviews')->insert([
                'booking_id'    => $m['id'],
                'user_id'       => $m['user_id'],
                'barber_id'     => $m['barber_id'],
                'barber_shop_id'=> $m['shop_id'],
                'barber_rating' => rand(3, 5),
                'shop_rating'   => rand(3, 5),
                'comment'       => $reviewComments[array_rand($reviewComments)],
                'created_at'    => now()->subDays(rand(1, 30)),
                'updated_at'    => now(),
            ]);
        }

        // 10. LOYALTY TRANSACTIONS for completed bookings
        foreach ($bookingMeta as $m) {
            if ($m['status'] !== 'completed') {
                continue;
            }
            if (rand(1, 10) > 5) {
                continue;
            }
            DB::table('loyalty_transactions')->insert([
                'user_id'     => $m['user_id'],
                'booking_id'  => $m['id'],
                'type'        => 'earn',
                'points'      => (int) floor($m['price'] / 10),
                'amount_rs'   => $m['price'],
                'description' => 'Points earned for completed booking',
                'created_at'  => now()->subDays(rand(1, 30)),
                'updated_at'  => now(),
            ]);
        }

        // 11. FAVOURITE SHOPS
        $favPairs = [];
        for ($f = 0; $f < 80; $f++) {
            $uid    = $customerIds[array_rand($customerIds)];
            $shopId = $approvedShops[array_rand($approvedShops)];
            $key    = $uid . '_' . $shopId;
            if (isset($favPairs[$key])) {
                continue;
            }
            $favPairs[$key] = true;
            DB::table('favourite_shops')->insert([
                'user_id'        => $uid,
                'barber_shop_id' => $shopId,
                'created_at'     => now()->subDays(rand(1, 60)),
                'updated_at'     => now(),
            ]);
        }

        // 12. CONTACT MESSAGES
        DB::table('contact_messages')->insert([
            ['name' => 'Aarav Sharma',   'email' => 'aarav.sharma@gmail.com',   'subject' => 'Booking not confirmed',   'message' => 'My booking was not confirmed even after payment. Please help.',         'status' => 'resolved', 'created_at' => now()->subDays(15), 'updated_at' => now()],
            ['name' => 'Priya Koirala',  'email' => 'priya.koirala@gmail.com',  'subject' => 'Refund not received',     'message' => 'I cancelled my booking 2 days ago but have not received a refund.',    'status' => 'resolved', 'created_at' => now()->subDays(10), 'updated_at' => now()],
            ['name' => 'Manish Poudel',  'email' => 'manish.poudel@gmail.com',  'subject' => 'Great experience!',       'message' => 'TrimTime has made booking so easy. Love the app. Keep it up!',         'status' => 'read',     'created_at' => now()->subDays(7),  'updated_at' => now()],
            ['name' => 'Rajan Khadka',   'email' => 'rajan.khadka@gmail.com',   'subject' => 'Cannot login',            'message' => 'I forgot my password and cannot access my account.',                   'status' => 'read',     'created_at' => now()->subDays(4),  'updated_at' => now()],
            ['name' => 'Sunita Joshi',   'email' => 'sunita.joshi@gmail.com',   'subject' => 'Partner registration',    'message' => 'I want to register my shop. How does the process work?',               'status' => 'read',     'created_at' => now()->subDays(3),  'updated_at' => now()],
            ['name' => 'Bibek Adhikari', 'email' => 'bibek.adhikari@gmail.com', 'subject' => 'Wrong barber assigned',   'message' => 'The barber I booked was different from who served me.',                'status' => 'unread',   'created_at' => now()->subDays(2),  'updated_at' => now()],
            ['name' => 'Sita Rai',       'email' => 'sita.rai@gmail.com',       'subject' => 'App crashing',            'message' => 'The app crashes every time I open the booking history page.',          'status' => 'unread',   'created_at' => now()->subDays(1),  'updated_at' => now()],
            ['name' => 'Nabin Lama',     'email' => 'nabin.lama2@gmail.com',    'subject' => 'Payment not reflected',   'message' => 'I paid via Khalti but booking still shows unpaid.',                    'status' => 'unread',   'created_at' => now()->subHours(5), 'updated_at' => now()],
            ['name' => 'Rekha Magar',    'email' => 'rekha.magar@gmail.com',    'subject' => 'Feature suggestion',      'message' => 'It would be great to have a chat feature with barbers before booking.','status' => 'unread',   'created_at' => now()->subHours(2), 'updated_at' => now()],
            ['name' => 'Pooja Shrestha', 'email' => 'pooja.shrestha@gmail.com', 'subject' => 'Loyalty points missing',  'message' => 'I completed 3 bookings but my loyalty points have not updated.',       'status' => 'unread',   'created_at' => now()->subHours(1), 'updated_at' => now()],
        ]);

        // 13. PARTNER REQUESTS
        DB::table('partner_requests')->insert([
            ['owner_name' => 'Prakash Oli',   'email' => 'prakash@freshlook.com', 'phone' => '9851111111', 'shop_name' => 'Fresh Style Pokhara',  'shop_address' => 'Lakeside Road, Pokhara',  'city' => 'Pokhara',    'district' => 'Kaski',    'number_of_barbers' => 3, 'services_offered' => 'Haircut, Beard Trim, Hair Colour', 'status' => 'pending',  'created_at' => now()->subDays(5),  'updated_at' => now()],
            ['owner_name' => 'Laxmi Gurung',  'email' => 'laxmi@stylehub.com',   'phone' => '9852222222', 'shop_name' => 'Grooming Hub Chitwan', 'shop_address' => 'Bharatpur-10, Chitwan',   'city' => 'Bharatpur',  'district' => 'Chitwan',  'number_of_barbers' => 2, 'services_offered' => 'Haircut, Facial, Hair Treatment',  'status' => 'pending',  'created_at' => now()->subDays(2),  'updated_at' => now()],
            ['owner_name' => 'Suresh Basnet', 'email' => 'suresh@moderncut.com', 'phone' => '9853333333', 'shop_name' => 'Modern Cut Biratnagar','shop_address' => 'Main Road, Biratnagar',   'city' => 'Biratnagar', 'district' => 'Morang',   'number_of_barbers' => 4, 'services_offered' => 'Haircut, Beard, Styling',           'status' => 'approved', 'created_at' => now()->subDays(20), 'updated_at' => now()],
            ['owner_name' => 'Prabha Tamang', 'email' => 'prabha@trimzone.com',  'phone' => '9854444444', 'shop_name' => 'Trim Zone Butwal',    'shop_address' => 'Butwal-9, Rupandehi',     'city' => 'Butwal',     'district' => 'Rupandehi','number_of_barbers' => 2, 'services_offered' => 'Haircut, Beard, Hair Colour',       'status' => 'rejected', 'created_at' => now()->subDays(30), 'updated_at' => now()],
        ]);

        // 14. WORKING HOURS (Mon-Sat open, Sunday closed)
        foreach ($barberIds as $bid) {
            for ($day = 0; $day <= 6; $day++) {
                DB::table('working_hours')->insert([
                    'barber_id'   => $bid,
                    'day_of_week' => $day,
                    'start_time'  => $day === 0 ? null : '09:00:00',
                    'end_time'    => $day === 0 ? null : '18:00:00',
                    'is_closed'   => $day === 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 15. SHOP CLOSED DAYS (Sunday closed)
        foreach ($approvedShops as $sid) {
            DB::table('shop_closed_days')->insert([
                'shop_id'     => $sid,
                'day_of_week' => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
