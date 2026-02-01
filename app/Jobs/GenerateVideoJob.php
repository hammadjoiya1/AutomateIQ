<?php

namespace App\Jobs;

use App\Models\ToolRun;
use App\Services\ReplicateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $runId;

    public function __construct(int $runId)
    {
        $this->runId = $runId;
    }

    public function handle(ReplicateService $replicate): void
    {
        $run = ToolRun::find($this->runId);

        if (!$run) {
            Log::warning('GenerateVideoJob: ToolRun not found', ['run_id' => $this->runId]);
            return;
        }

        try {
            $input = $run->input_data ?? [];
            $prompt = $input['input'] ?? '';
            $prediction = $replicate->generateVideo($prompt);

            $run->update([
                'output_text' => 'VIDEO_GENERATION_STARTED: ' . ($prediction['id'] ?? ''),
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::error('GenerateVideoJob failed', ['run_id' => $this->runId, 'error' => $e->getMessage()]);
            $run->update([
                'output_text' => 'Error: ' . $e->getMessage(),
                'status' => 'failed',
            ]);
        }
    }
}
