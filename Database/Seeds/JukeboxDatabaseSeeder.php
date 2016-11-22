<?php

namespace App\Modules\Jukebox;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class JukeboxDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Model::unguard();

        $this->call(RepresentSeeder::class);
        $this->call(SongSeeder::class);
        $this->call(WeixinUserSeeder::class);
        $this->call(SongSheetSeeder::class);
        $this->call(AlbumSeeder::class);

        Model::reguard();
	}
}
