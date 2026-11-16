<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\ForumBoard;
use App\Models\ForumThread;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Марина',
            'nickname' => 'VioletFox',
            'email' => 'admin@furhouse.local',
            'password' => Hash::make('password'),
            'avatar' => 'team/admin.jpg',
            'role' => User::ROLE_ADMIN,
            'city' => 'Санкт-Петербург',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Moderator
        $moderator = User::create([
            'name' => 'Илья',
            'nickname' => 'NorthPaw',
            'email' => 'mod@furhouse.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MODERATOR,
            'city' => 'Москва',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Developer
        $dev = User::create([
            'name' => 'Соня',
            'nickname' => 'PixelLynx',
            'email' => 'dev@furhouse.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'city' => 'Казань',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Master
        $master = User::create([
            'name' => 'Алексей',
            'nickname' => 'CraftBear',
            'email' => 'master@furhouse.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MASTER,
            'city' => 'Екатеринбург',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Organizer
        $organizer = User::create([
            'name' => 'Ольга',
            'nickname' => 'SunnyRabbit',
            'email' => 'organizer@furhouse.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ORGANIZER,
            'city' => 'Санкт-Петербург',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Regular users
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => fake()->firstName(),
                'nickname' => fake()->userName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'city' => fake()->randomElement(['Москва', 'Санкт-Петербург', 'Казань', 'Екатеринбург', 'Новосибирск']),
                'is_verified' => fake()->boolean(30),
                'email_verified_at' => now(),
            ]);
        }

        // Categories
        $categories = [
            ['name' => 'Арт и дизайн', 'slug' => 'art-design', 'icon' => 'palette', 'sort_order' => 1],
            ['name' => 'Костюмы', 'slug' => 'costumes', 'icon' => 'scissors', 'sort_order' => 2],
            ['name' => 'Аксессуары', 'slug' => 'accessories', 'icon' => 'gem', 'sort_order' => 3],
            ['name' => 'Одежда', 'slug' => 'clothing', 'icon' => 'handbag', 'sort_order' => 4],
            ['name' => 'Стикеры', 'slug' => 'stickers', 'icon' => 'stars', 'sort_order' => 5],
            ['name' => 'Пины и значки', 'slug' => 'pins', 'icon' => 'pin', 'sort_order' => 6],
            ['name' => 'Артбуки', 'slug' => 'artbooks', 'icon' => 'book', 'sort_order' => 7],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $productData = [
            ['title' => 'Худи «Лунный след»', 'price' => 5800, 'category' => 4, 'user' => $master->id],
            ['title' => 'Парные брелоки', 'price' => 1250, 'category' => 3, 'user' => $master->id],
            ['title' => 'Референс персонажа', 'price' => 7000, 'category' => 1, 'user' => $master->id],
            ['title' => 'Ушки «Сумерки»', 'price' => 3400, 'category' => 2, 'user' => $master->id],
            ['title' => 'Пин «Домой»', 'price' => 590, 'category' => 6, 'user' => $master->id],
            ['title' => 'Артбук «Соседи»', 'price' => 2100, 'category' => 7, 'user' => $master->id],
            ['title' => 'Лавандовый ошейник', 'price' => 2490, 'category' => 3, 'user' => $master->id],
            ['title' => 'Набор лесных стикеров', 'price' => 690, 'category' => 5, 'user' => $master->id],
            ['title' => 'Маска «Северный волк»', 'price' => 18900, 'category' => 2, 'user' => $master->id],
        ];

        foreach ($productData as $data) {
            Product::create([
                'user_id' => $data['user'],
                'category_id' => $data['category'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(6),
                'description' => 'Авторская работа от проверенного мастера. Высокое качество материалов и исполнения.',
                'price' => $data['price'],
                'status' => 'active',
                'in_stock' => true,
                'rating' => 4.9,
                'reviews_count' => rand(10, 50),
                'views_count' => rand(100, 1000),
                'favorites_count' => rand(20, 200),
                'delivery_type' => 'russia',
            ]);
        }

        // Forum boards
        $boards = [
            ['name' => 'Общее', 'slug' => 'general', 'prefix' => 'fth', 'sort_order' => 1],
            ['name' => 'Творчество', 'slug' => 'art', 'prefix' => 'art', 'sort_order' => 2],
            ['name' => 'Крафт и костюмы', 'slug' => 'craft', 'prefix' => 'craft', 'sort_order' => 3],
            ['name' => 'Встречи', 'slug' => 'meet', 'prefix' => 'meet', 'sort_order' => 4],
            ['name' => 'Игры', 'slug' => 'play', 'prefix' => 'play', 'sort_order' => 5],
            ['name' => 'Помощь новичкам', 'slug' => 'start', 'prefix' => 'start', 'sort_order' => 6],
        ];

        foreach ($boards as $board) {
            ForumBoard::create($board);
        }

        // Forum threads
        $threads = [
            ['board' => 2, 'title' => 'Покажите любимый осенний скетч', 'body' => 'Собираем тёплую подборку работ недели. Можно делиться процессом и материалами.', 'user' => $admin->id],
            ['board' => 3, 'title' => 'Как аккуратно поставить магнит в уши?', 'body' => 'Первый крафт-проект. Нужен совет по толщине основания и безопасному клею.', 'user' => $moderator->id],
            ['board' => 4, 'title' => 'Фото с пикника в Казани', 'body' => 'Спасибо всем за спокойный день! Делимся общими кадрами и идеями для следующей встречи.', 'user' => $organizer->id],
            ['board' => 6, 'title' => 'Небольшой словарь для новичка', 'body' => 'Собрали понятные объяснения частых слов сообщества. Дополнения приветствуются.', 'user' => $dev->id],
            ['board' => 5, 'title' => 'Во что играем в пятницу?', 'body' => 'Выбираем кооперативную игру на 4–6 человек. Без рейтинга, просто уютный вечер.', 'user' => $master->id],
        ];

        foreach ($threads as $data) {
            ForumThread::create([
                'board_id' => $data['board'],
                'user_id' => $data['user'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(6),
                'body' => $data['body'],
                'last_activity_at' => now()->subMinutes(rand(10, 1000)),
                'replies_count' => rand(5, 50),
                'views_count' => rand(100, 500),
            ]);
        }

        // Events
        Event::create([
            'user_id' => $organizer->id,
            'title' => 'Тихая прогулка у залива',
            'slug' => 'walk-bay-' . Str::random(6),
            'description' => 'Спокойная осенняя прогулка вдоль набережной. Обсудим творческие планы, познакомимся с новыми участниками сообщества.',
            'short_description' => 'Осенняя прогулка для небольших компаний',
            'type' => 'walk',
            'city' => 'Санкт-Петербург',
            'location' => 'Набережная у залива',
            'starts_at' => now()->addDays(3)->setHour(14)->setMinute(0),
            'max_guests' => 12,
            'price' => 0,
            'is_free' => true,
            'is_verified' => true,
            'is_featured' => true,
            'status' => 'approved',
            'participants_count' => 8,
        ]);

        Event::create([
            'user_id' => $organizer->id,
            'title' => 'Скетч-вечер в мастерской',
            'slug' => 'sketch-evening-' . Str::random(6),
            'description' => 'Творческий вечер в уютной мастерской. Рисуйте, общайтесь, делитесь идеями.',
            'type' => 'creative',
            'city' => 'Москва',
            'starts_at' => now()->addDays(9)->setHour(18)->setMinute(30),
            'max_guests' => 16,
            'price' => 900,
            'is_free' => false,
            'is_verified' => true,
            'status' => 'approved',
            'participants_count' => 12,
        ]);

        Event::create([
            'user_id' => $organizer->id,
            'title' => 'Фурри-пикник «Золотой лес»',
            'slug' => 'furry-picnic-' . Str::random(6),
            'description' => 'Осенний пикник в парке. Настольные игры, угощения и хорошая компания.',
            'type' => 'picnic',
            'city' => 'Казань',
            'starts_at' => now()->addDays(11)->setHour(12)->setMinute(0),
            'max_guests' => 24,
            'price' => 500,
            'is_free' => false,
            'is_verified' => true,
            'status' => 'approved',
            'participants_count' => 18,
        ]);

        Event::create([
            'user_id' => $organizer->id,
            'title' => 'Настольный вечер',
            'slug' => 'boardgames-evening-' . Str::random(6),
            'description' => 'Вечер настольных игр для всех желающих. Принесите свои любимые игры!',
            'type' => 'games',
            'city' => 'Екатеринбург',
            'starts_at' => now()->addDays(17)->setHour(17)->setMinute(0),
            'max_guests' => 10,
            'price' => 700,
            'is_free' => false,
            'is_verified' => true,
            'status' => 'approved',
            'participants_count' => 7,
        ]);

        // Services
        $services = [
            ['user' => $master->id, 'title' => 'Иллюстрация персонажа', 'category' => 'art', 
            'price_from' => 5500, 'deadline' => '7-10 дней', 'rating' => 5.0, 'reviews' => 32],
            ['user' => $master->id, 'title' => 'Голова фурсьюта', 'category' => 'costume', 
            'price_from' => 42000, 'deadline' => '6-8 недель', 'rating' => 4.9, 'reviews' => 32],
            ['user' => $master->id, 'title' => 'Дизайн референса', 'category' => 'design', 
            'price_from' => 8000, 'deadline' => '10-14 дней', 'rating' => 4.8, 'reviews' => 32],
            ['user' => $master->id, 'title' => 'Музыкальная тема', 'category' => 'music', 
            'price_from' => 12000, 'deadline' => '14-20 дней', 'rating' => 5.0, 'reviews' => 32],
            ['user' => $master->id, 'title' => '3D-модель аватара', 'category' => '3d', 
            'price_from' => 24000, 'deadline' => '3-4 недели', 'rating' => 4.9, 'reviews' => 32],
            ['user' => $master->id, 'title' => 'Лапы и хвост', 'category' => 'craft', 
            'price_from' => 16500, 'deadline' => '4-6 недель', 'rating' => 4.9, 'reviews' => 32],
        ];

        foreach ($services as $data) {
            Service::create([
                'user_id' => $data['user'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(6),
                'description' => 'Профессиональное выполнение заказа с учётом всех пожеланий.',
                'category' => $data['category'],
                'price_from' => $data['price_from'],  // ← исправлено
                'deadline' => $data['deadline'],
                'rating' => $data['rating'],
                'reviews_count' => $data['reviews'],
                'is_active' => true,
                'accepts_orders' => true,
                'orders_count' => rand(10, 50),
            ]);
        }
    }
}