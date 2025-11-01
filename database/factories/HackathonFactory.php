<?php

namespace Database\Factories;

use App\HackathonFormat;
use App\SkillLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hackathon>
 */
class HackathonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s').' +14 days');
        $registrationDeadline = fake()->dateTimeBetween('now', $startDate->format('Y-m-d H:i:s').' -1 day');

        $themes = [
            'AI Innovation Challenge',
            'Blockchain Revolution',
            'Web3 Development',
            'Sustainable Tech Solutions',
            'Healthcare Innovation',
            'FinTech Future',
            'Climate Action Hackathon',
            'EdTech Transformation',
            'Gaming Innovation',
            'Cybersecurity Challenge',
        ];

        $technologies = [
            ['Python', 'Machine Learning', 'AI/ML', 'TensorFlow'],
            ['Solidity', 'Web3', 'Blockchain', 'Smart Contracts'],
            ['JavaScript', 'React', 'Node.js', 'Web Development'],
            ['Python', 'Data Science', 'Analytics', 'Visualization'],
            ['Mobile', 'React Native', 'Flutter', 'iOS', 'Android'],
            ['Cybersecurity', 'Ethical Hacking', 'Security'],
            ['Cloud', 'AWS', 'Azure', 'DevOps'],
        ];

        $skillLevels = array_column(SkillLevel::cases(), 'value');
        $formats = array_column(HackathonFormat::cases(), 'value');

        return [
            'title' => fake()->randomElement($themes),
            'description' => fake()->paragraphs(3, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_deadline' => $registrationDeadline,
            'max_participants' => fake()->optional(0.7)->randomElement([50, 100, 200, 500, 1000]),
            'max_team_size' => fake()->numberBetween(3, 6),
            'min_team_size' => 2,
            'entry_fee' => fake()->randomElement([0, 0, 0, 10, 25, 50]),
            'prize_pool' => fake()->optional(0.8)->randomElement([5000, 10000, 25000, 50000, 100000, 150000, 200000]),
            'location' => fake()->randomElement(['Online', 'San Francisco, CA', 'New York, NY', 'Austin, TX', 'London, UK', 'Berlin, Germany', 'Singapore']),
            'format' => fake()->randomElement($formats),
            'skill_requirements' => fake()->randomElement($skillLevels),
            'technologies' => fake()->randomElement($technologies),
            'rules' => fake()->optional(0.5)->paragraphs(2, true),
            'is_active' => true,
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
