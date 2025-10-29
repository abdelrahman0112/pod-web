<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing events and categories (handle foreign key constraints)
        Event::query()->delete();
        EventCategory::query()->delete();

        // Get a user to be the creator (or create one if none exists)
        $user = User::first();
        if (! $user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Create 6 event categories
        $categories = [
            [
                'name' => 'Technology',
                'color' => '#3B82F6',
                'description' => 'Tech conferences, workshops, and meetups',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'color' => '#10B981',
                'description' => 'Business networking and entrepreneurship events',
                'is_active' => true,
            ],
            [
                'name' => 'Design',
                'color' => '#F59E0B',
                'description' => 'UI/UX design, graphic design, and creative workshops',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'color' => '#EF4444',
                'description' => 'Digital marketing, social media, and branding events',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'color' => '#8B5CF6',
                'description' => 'Learning workshops, courses, and educational seminars',
                'is_active' => true,
            ],
            [
                'name' => 'Networking',
                'color' => '#06B6D4',
                'description' => 'Professional networking and community building events',
                'is_active' => true,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $createdCategories[] = EventCategory::create($categoryData);
        }

        // Event formats
        $formats = ['online', 'in-person', 'hybrid'];

        // Generate events for each category (4-15 events per category)
        foreach ($createdCategories as $category) {
            $eventCount = rand(4, 15);

            for ($i = 1; $i <= $eventCount; $i++) {
                $startDate = Carbon::now()->addDays(rand(1, 90));
                $endDate = $startDate->copy()->addHours(rand(2, 8));
                $registrationDeadline = $startDate->copy()->subDays(rand(1, 7));

                Event::create([
                    'title' => $this->generateEventTitle($category->name, $i),
                    'description' => $this->generateEventDescription($category->name),
                    'format' => $formats[array_rand($formats)],
                    'category_id' => $category->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'location' => $this->generateLocation(),
                    'max_attendees' => rand(20, 200),
                    'agenda' => $this->generateAgenda(),
                    'banner_image' => null,
                    'registration_deadline' => $registrationDeadline,
                    'waitlist_enabled' => rand(0, 1),
                    'is_active' => true,
                    'chat_opens_at' => $startDate->copy()->subHours(1),
                    'created_by' => $user->id,
                ]);
            }
        }

        $this->command->info('Created '.count($createdCategories).' categories and '.Event::count().' events');
    }

    private function generateEventTitle($category, $index)
    {
        $titles = [
            'Technology' => [
                'AI & Machine Learning Summit',
                'Web Development Workshop',
                'Cybersecurity Conference',
                'Cloud Computing Meetup',
                'Mobile App Development',
                'Data Science Bootcamp',
                'DevOps Best Practices',
                'Blockchain Technology',
                'IoT Innovation Summit',
                'Tech Startup Pitch',
                'Software Architecture',
                'Digital Transformation',
                'Tech Leadership Forum',
                'Innovation Lab',
                'Tech Career Fair',
            ],
            'Business' => [
                'Entrepreneurship Summit',
                'Business Networking Mixer',
                'Startup Pitch Competition',
                'Business Strategy Workshop',
                'Investment Opportunities',
                'Business Growth Seminar',
                'Leadership Development',
                'Business Model Canvas',
                'Market Analysis Workshop',
                'Business Plan Competition',
                'Corporate Innovation',
                'Business Ethics Forum',
                'Business Technology',
                'Business Networking',
                'Business Success Stories',
            ],
            'Design' => [
                'UI/UX Design Workshop',
                'Graphic Design Masterclass',
                'Design Thinking Session',
                'Creative Portfolio Review',
                'Design System Workshop',
                'Brand Identity Design',
                'User Research Methods',
                'Design Tools Training',
                'Creative Collaboration',
                'Design Portfolio Showcase',
                'Design Innovation Lab',
                'Design Career Workshop',
                'Creative Design Challenge',
                'Design Community Meetup',
                'Design Leadership Forum',
            ],
            'Marketing' => [
                'Digital Marketing Summit',
                'Social Media Strategy',
                'Content Marketing Workshop',
                'SEO & SEM Training',
                'Email Marketing Masterclass',
                'Marketing Analytics',
                'Brand Marketing Strategy',
                'Influencer Marketing',
                'Marketing Automation',
                'Marketing ROI Workshop',
                'Marketing Trends 2024',
                'Marketing Career Development',
                'Marketing Innovation Lab',
                'Marketing Community',
                'Marketing Leadership',
            ],
            'Education' => [
                'Learning & Development Summit',
                'Educational Technology',
                'Online Learning Workshop',
                'Teaching Methods Training',
                'Educational Innovation',
                'Learning Analytics',
                'Educational Leadership',
                'Curriculum Development',
                'Educational Research',
                'Learning Community',
                'Educational Policy Forum',
                'Educational Technology',
                'Learning Design Workshop',
                'Educational Assessment',
                'Learning Innovation Lab',
            ],
            'Networking' => [
                'Professional Networking Mixer',
                'Industry Networking Event',
                'Career Networking Session',
                'Business Networking Meetup',
                'Tech Networking Event',
                'Creative Networking Mixer',
                'Entrepreneur Networking',
                'Women in Tech Networking',
                'Startup Networking Event',
                'Professional Development',
                'Networking Skills Workshop',
                'Community Building',
                'Professional Community',
                'Networking Best Practices',
                'Networking Success Stories',
            ],
        ];

        $categoryTitles = $titles[$category] ?? ['General Event'];

        return $categoryTitles[($index - 1) % count($categoryTitles)];
    }

    private function generateEventDescription($category)
    {
        $descriptions = [
            'Technology' => 'Join us for an exciting technology event featuring industry experts, hands-on workshops, and networking opportunities. Learn about the latest trends and innovations in technology.',
            'Business' => 'Connect with fellow entrepreneurs and business professionals. This event offers valuable insights, networking opportunities, and strategies for business growth.',
            'Design' => 'Explore the world of design with creative professionals. Learn new techniques, get feedback on your work, and connect with the design community.',
            'Marketing' => 'Discover the latest marketing strategies and tools. Learn from industry experts and network with marketing professionals.',
            'Education' => 'Enhance your learning and teaching skills. This educational event provides valuable resources and networking opportunities for educators.',
            'Networking' => 'Build meaningful professional connections. This networking event brings together professionals from various industries for collaboration and growth.',
        ];

        return $descriptions[$category] ?? 'Join us for an exciting event featuring industry experts and networking opportunities.';
    }

    private function generateLocation()
    {
        $locations = [
            'Conference Center, Downtown',
            'Tech Hub, Innovation District',
            'Business Center, Financial District',
            'Creative Space, Arts Quarter',
            'University Campus, Education District',
            'Co-working Space, Startup Hub',
            'Hotel Conference Room, Business District',
            'Community Center, City Center',
            'Online Event (Virtual)',
            'Hybrid Event (Online + In-Person)',
        ];

        return $locations[array_rand($locations)];
    }

    private function generateAgenda()
    {
        $agendas = [
            '9:00 AM - Registration & Welcome\n10:00 AM - Keynote Presentation\n11:00 AM - Break\n11:15 AM - Workshop Session 1\n12:30 PM - Lunch Break\n1:30 PM - Workshop Session 2\n2:45 PM - Break\n3:00 PM - Panel Discussion\n4:00 PM - Networking Session\n5:00 PM - Closing Remarks',
            '10:00 AM - Welcome & Introductions\n10:30 AM - Industry Trends Presentation\n11:15 AM - Coffee Break\n11:30 AM - Hands-on Workshop\n12:30 PM - Lunch & Networking\n1:30 PM - Case Study Discussion\n2:30 PM - Break\n2:45 PM - Q&A Session\n3:30 PM - Closing & Next Steps',
            '9:30 AM - Registration\n10:00 AM - Opening Remarks\n10:30 AM - Expert Panel\n11:30 AM - Break\n11:45 AM - Interactive Workshop\n12:45 PM - Lunch\n1:45 PM - Breakout Sessions\n2:45 PM - Break\n3:00 PM - Final Presentations\n4:00 PM - Networking Reception',
        ];

        return $agendas[array_rand($agendas)];
    }
}
