<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemPhoto;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CDBDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $testUser = User::firstOrCreate([
                'name' => 'Test user',
                'email' => 'test@test.com',
                'password' => Hash::make('password'),
            ]);

            $users = User::factory()->count(30)->create();
            $users->push($testUser);

            $categories = Category::all();

            $items = Item::factory()
                ->count(120)
                ->make()
                ->each(function (Item $item) use ($users, $categories) {
                    $item->user_id = $users->random()->id;
                    $item->category_id = $categories->random()->id;
                    $item->save();
                });

            foreach ($items as $item) {

                $photoCount = random_int(1, 3);
                for ($i = 0; $i < $photoCount; $i++) {
                    ItemPhoto::create([
                        'item_id' => $item->id,
                        'path' => $this->makeSeedImage($item->title),
                    ]);
                }

                $commentCount = random_int(0, 8);
                for ($i = 0; $i < $commentCount; $i++) {
                    Comment::create([
                        'item_id' => $item->id,
                        'user_id' => $users->random()->id,
                        'body' => fake()->sentences(random_int(1, 3), true),
                    ]);
                }

                $voterCount = random_int(0, 20);
                $voters = $users->random(min($voterCount, $users->count()));
                $score = 0;

                foreach ($voters as $u) {
                    $value = fake()->randomElement([1, -1]);

                    Vote::updateOrCreate(
                        ['item_id' => $item->id, 'user_id' => $u->id],
                        ['value' => $value]
                    );

                    $score += $value;
                }

                $item->update(['score' => $score]);
            }
        });
    }

    private function makeSeedImage(string $label = 'GiftShare'): string
    {
        Storage::disk('public')->makeDirectory('seed');

        $filename = 'seed/' . fake()->uuid() . '.jpg';

        if (! function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('GD extension is not enabled. Enable php-gd to generate seed images.');
        }

        $w = 900;
        $h = 600;

        $img = imagecreatetruecolor($w, $h);

        $bg = imagecolorallocate($img, random_int(20, 230), random_int(20, 230), random_int(20, 230));
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $overlay = imagecolorallocatealpha($img, 255, 255, 255, 110);
        for ($x = 0; $x < $w; $x += 40) {
            imageline($img, $x, 0, $x - 200, $h, $overlay);
        }

        $textColor = imagecolorallocate($img, 20, 20, 20);
        $text = $label;

        imagestring($img, 5, 20, 20, $text, $textColor);
        imagestring($img, 4, 20, 55, 'Seed photo', $textColor);

        ob_start();
        imagejpeg($img, null, 85);
        $binary = ob_get_clean();

        imagedestroy($img);

        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }

}
