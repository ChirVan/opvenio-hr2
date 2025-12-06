<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'John Roy Dadap',
            'email' => 'royvinsondadap@gmail.com',
            'password' => bcrypt('password123'), // known password for local testing
        ]);

        $this->call(\Database\Seeders\EmployeesTableSeeder::class);
        
        // Competency Management seeders
        $this->call(\Database\Seeders\CompetencyFrameworkSeeder::class);
        $this->call(\Database\Seeders\CompetencySeeder::class);
        
        // Training Management seeders
        $this->call(\Database\Seeders\TrainingCatalogSeeder::class);
        $this->call(\Database\Seeders\TrainingMaterialSeeder::class);
        
        // Learning Management seeders
        $this->call(\Database\Seeders\AssessmentCategorySeeder::class);
        $this->call(\Database\Seeders\QuizSeeder::class);
        $this->call(\Database\Seeders\QuizQuestionSeeder::class);
    }
}
