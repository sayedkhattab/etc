<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Assessment;
use App\Models\CourseContent;
use App\Models\Level;
use Illuminate\Support\Facades\DB;

class LinkQuestionsToContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pretest assessments
        $pretestAssessments = Assessment::where('is_pretest', true)->get();
        
        foreach ($pretestAssessments as $assessment) {
            // Get the level for this assessment
            $level = $assessment->level;
            if (!$level) continue;
            
            // Get all content for this level
            $contents = CourseContent::where('level_id', $level->id)->get();
            if ($contents->isEmpty()) continue;
            
            // Get all questions for this assessment
            $questions = $assessment->questions()->whereNull('content_id')->get();
            
            if ($questions->isEmpty()) {
                $this->command->info("No questions without content_id found for assessment {$assessment->id}");
                continue;
            }
            
            $this->command->info("Linking {$questions->count()} questions to content for assessment {$assessment->id} (level {$level->id})");
            
            // Link each question to a content
            foreach ($questions as $index => $question) {
                // Get a content (cycle through available content)
                $content = $contents[$index % $contents->count()];
                
                // Update the question to link it to the content
                $question->content_id = $content->id;
                $question->save();
                
                $this->command->info("Linked question {$question->id} to content {$content->id} ({$content->title})");
            }
        }
        
        // Print summary
        $linkedQuestionsCount = Question::whereNotNull('content_id')->count();
        $this->command->info("Total questions linked to content: {$linkedQuestionsCount}");
    }
} 