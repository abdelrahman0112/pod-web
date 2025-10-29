<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to create posts for
        $users = User::take(5)->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');

            return;
        }

        $posts = [
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Just completed an amazing project using transformer models for Arabic NLP. The results exceeded our expectations! Would love to share insights with the community. Who else is working on Arabic language processing?',
                'hashtags' => ['#MachineLearning', '#ArabicNLP', '#Transformers'],
                'likes_count' => 24,
                'comments_count' => 8,
                'shares_count' => 3,
                'is_published' => true,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'image',
                'content' => 'Looking for recommendations on the best data visualization tools for executive dashboards. Currently evaluating Tableau vs Power BI. What\'s your experience with these platforms?',
                'images' => ['posts/dashboard-preview.jpg'],
                'hashtags' => ['#DataVisualization', '#Tableau', '#PowerBI'],
                'likes_count' => 18,
                'comments_count' => 12,
                'shares_count' => 5,
                'is_published' => true,
                'created_at' => Carbon::now()->subHours(4),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Excited to announce that our research paper on "Deep Learning Applications in Healthcare" has been accepted at ICML 2024! Special thanks to the amazing team and the supportive community here. 🎉',
                'hashtags' => ['#DeepLearning', '#Healthcare', '#Research', '#ICML2024'],
                'likes_count' => 42,
                'comments_count' => 15,
                'shares_count' => 8,
                'is_published' => true,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Looking for advice on scaling deep learning models for production. We\'re dealing with 100M+ parameters and need to optimize inference time. Any suggestions on model compression techniques?',
                'hashtags' => ['#DeepLearning', '#ModelOptimization', '#Production'],
                'likes_count' => 18,
                'comments_count' => 12,
                'shares_count' => 2,
                'is_published' => true,
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Excited to share my latest project on predicting customer churn using ensemble methods. Achieved 94% accuracy with Random Forest + XGBoost combination. Code and dataset available on GitHub!',
                'hashtags' => ['#DataScience', '#CustomerAnalytics', '#MachineLearning'],
                'likes_count' => 42,
                'comments_count' => 15,
                'shares_count' => 7,
                'is_published' => true,
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Great discussion at yesterday\'s meetup about ethical AI! Key takeaway: We need more diverse datasets and transparent algorithms. Let\'s continue building responsible AI solutions in Egypt.',
                'hashtags' => ['#EthicalAI', '#ResponsibleAI', '#Meetup'],
                'likes_count' => 31,
                'comments_count' => 9,
                'shares_count' => 4,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Best practices for deploying ML models in production environments. Sharing my experience with containerization, monitoring, and A/B testing. What challenges have you faced?',
                'hashtags' => ['#MLOps', '#Production', '#Deployment'],
                'likes_count' => 32,
                'comments_count' => 18,
                'shares_count' => 6,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Introduction to Graph Neural Networks for beginners. Just published a comprehensive tutorial covering the basics and practical applications. Perfect for those starting their GNN journey!',
                'hashtags' => ['#GraphNeuralNetworks', '#Tutorial', '#DeepLearning'],
                'likes_count' => 28,
                'comments_count' => 12,
                'shares_count' => 9,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Career transition from software engineering to data science. Sharing my journey, the skills I learned, and tips for others considering the switch. Happy to answer questions!',
                'hashtags' => ['#CareerTransition', '#DataScience', '#SoftwareEngineering'],
                'likes_count' => 25,
                'comments_count' => 22,
                'shares_count' => 11,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Working on a fascinating computer vision project for medical image analysis. The potential for AI to assist doctors in diagnosis is incredible. Anyone else in the medical AI space?',
                'hashtags' => ['#ComputerVision', '#MedicalAI', '#Healthcare'],
                'likes_count' => 35,
                'comments_count' => 14,
                'shares_count' => 8,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Just finished a comprehensive analysis of Egypt\'s tech job market trends. The demand for AI and data science roles has increased by 150% this year! Full report coming soon.',
                'hashtags' => ['#JobMarket', '#TechTrends', '#Egypt'],
                'likes_count' => 29,
                'comments_count' => 16,
                'shares_count' => 13,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Building a recommendation system for an e-commerce platform. The challenge of handling cold start problems and ensuring real-time recommendations is fascinating!',
                'hashtags' => ['#RecommendationSystems', '#Ecommerce', '#ColdStart'],
                'likes_count' => 21,
                'comments_count' => 8,
                'shares_count' => 3,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Exploring the intersection of AI and climate change. Working on models to predict weather patterns and optimize renewable energy distribution. The impact potential is huge!',
                'hashtags' => ['#ClimateAI', '#WeatherPrediction', '#RenewableEnergy'],
                'likes_count' => 38,
                'comments_count' => 19,
                'shares_count' => 15,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Just completed a machine learning bootcamp and landed my first data science role! The community support here was invaluable. Thank you all for the guidance and encouragement.',
                'hashtags' => ['#CareerSuccess', '#Bootcamp', '#DataScience'],
                'likes_count' => 45,
                'comments_count' => 28,
                'shares_count' => 12,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'text',
                'content' => 'Working on natural language processing for Arabic text. The morphological complexity of Arabic presents unique challenges but also exciting opportunities for innovation.',
                'hashtags' => ['#ArabicNLP', '#NaturalLanguageProcessing', '#Morphology'],
                'likes_count' => 33,
                'comments_count' => 17,
                'shares_count' => 7,
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(11),
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }

        $this->command->info('Created '.count($posts).' demo posts.');
    }
}
