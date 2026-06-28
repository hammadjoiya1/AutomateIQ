<?php

namespace App\Services;

class ScriptParserService
{
    /**
     * Tone-to-voice mapping for OpenAI TTS.
     * Each tone maps to an optimal voice and speech speed.
     */
    protected array $toneProfiles = [
        'whisper' => [
            'voice' => 'onyx',
            'speed' => 0.85,
            'keywords' => ['whisper', 'sinister', 'creepy', 'eerie', 'murmur', 'hiss', 'quiet', 'soft voice', 'faint'],
        ],
        'suspenseful' => [
            'voice' => 'onyx',
            'speed' => 0.90,
            'keywords' => ['suspense', 'suspenseful', 'dark', 'haunting', 'waiting', 'watching', 'shadow', 'lurk', 'silence', 'tension', 'ominous', 'dread', 'fear', 'mask', 'ghostface'],
        ],
        'intense' => [
            'voice' => 'echo',
            'speed' => 1.10,
            'keywords' => ['punchy', 'rapid', 'montage', 'action', 'bloody', 'chase', 'run', 'panic', 'scream', 'attack', 'fast', 'rush', 'body count', 'deadlier', 'risen'],
        ],
        'dramatic' => [
            'voice' => 'fable',
            'speed' => 0.95,
            'keywords' => ['dramatic', 'epic', 'franchise', 'returns', 'final', 'legacy', 'title card', 'loudest', 'twist', 'hunts', 'past', 'haunt'],
        ],
        'calm' => [
            'voice' => 'nova',
            'speed' => 0.90,
            'keywords' => ['calm', 'peaceful', 'serene', 'gentle', 'quiet', 'still', 'fade', 'coming soon'],
        ],
        'neutral' => [
            'voice' => 'alloy',
            'speed' => 1.0,
            'keywords' => [],
        ],
    ];

    /**
     * Parse a single raw script line into structured components.
     *
     * Input example:
     *   '0:00-0:10 Slow zoom on the iconic Ghostface mask hanging in the dark.(Sound of a landline phone ringing, then a sharp silence) "He\'s been waiting. He\'s been watching."'
     *
     * Output:
     *   [
     *       'timecode'   => '0:00-0:10',
     *       'visual'     => 'Slow zoom on the iconic Ghostface mask hanging in the dark.',
     *       'sound_cues' => 'Sound of a landline phone ringing, then a sharp silence',
     *       'dialogue'   => "He's been waiting. He's been watching.",
     *       'tone'       => 'suspenseful',
     *       'voice'      => 'onyx',
     *       'speed'      => 0.90,
     *       'raw'        => '(original line)',
     *   ]
     */
    public function parseLine(string $line): array
    {
        $line = trim($line);
        $raw = $line;

        // 1. Extract timecode (e.g., 0:00-0:10, 0:55-60, 1:00-1:15)
        $timecode = null;
        if (preg_match('/^(\d+:\d{2}\s*-\s*\d+:?\d{0,2})\s*/', $line, $tcMatch)) {
            $timecode = trim($tcMatch[1]);
            $line = trim(substr($line, strlen($tcMatch[0])));
        }

        // 2. Extract all sound cues in parentheses: (Sound of ...)
        $soundCues = [];
        if (preg_match_all('/\(([^)]+)\)/', $line, $scMatches)) {
            $soundCues = $scMatches[1];
            // Remove sound cues from the line
            $line = preg_replace('/\([^)]+\)/', '', $line);
        }

        // 3. Extract all dialogue in quotes (both regular and smart quotes)
        $dialogue = [];

        // Regular double quotes
        if (preg_match_all('/"([^"]+)"/', $line, $dqMatches)) {
            $dialogue = array_merge($dialogue, $dqMatches[1]);
            $line = preg_replace('/"[^"]+"/', '', $line);
        }

        // Smart/curly double quotes
        if (preg_match_all('/\x{201C}([^\x{201D}]+)\x{201D}/u', $line, $sqMatches)) {
            $dialogue = array_merge($dialogue, $sqMatches[1]);
            $line = preg_replace('/\x{201C}[^\x{201D}]+\x{201D}/u', '', $line);
        }

        // 4. What remains is the pure visual description
        $visual = trim(preg_replace('/\s{2,}/', ' ', $line));
        // Clean up trailing/leading punctuation artifacts
        $visual = trim($visual, " \t\n\r\0\x0B.,;:");

        // 5. Detect tone from the FULL raw context (visual + dialogue + sound cues)
        $fullContext = strtolower($raw);
        $detectedTone = $this->detectTone($fullContext);
        $profile = $this->toneProfiles[$detectedTone];

        return [
            'timecode'   => $timecode,
            'visual'     => $visual,
            'sound_cues' => implode('; ', $soundCues),
            'dialogue'   => implode(' ', $dialogue),
            'tone'       => $detectedTone,
            'voice'      => $profile['voice'],
            'speed'      => $profile['speed'],
            'raw'        => $raw,
        ];
    }

    /**
     * Parse a full script (multi-line or continuous block) into an array of parsed scenes.
     */
    public function parseScript(string $script): array
    {
        // First, try splitting by newlines
        $lines = array_filter(array_map('trim', explode("\n", $script)));

        // If pasted as a single block, split by timecodes
        if (count($lines) <= 1) {
            $text = trim($script);
            if (preg_match('/\d+:\d{2}\s*-\s*\d+:?\d{0,2}/', $text)) {
                $lines = preg_split('/(?=\d+:\d{2}\s*-\s*\d+:?\d{0,2})/', $text, -1, PREG_SPLIT_NO_EMPTY);
                $lines = array_filter(array_map('trim', $lines));
            } else {
                // Fallback: split by sentences
                $lines = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
                $lines = array_filter(array_map('trim', $lines));
            }
        }

        $scenes = [];
        foreach ($lines as $line) {
            if (strlen($line) < 5) {
                continue;
            }
            $scenes[] = $this->parseLine($line);
        }

        return $scenes;
    }

    /**
     * Detect the emotional tone of a script line using keyword matching.
     * Returns the tone with the highest keyword match score.
     */
    protected function detectTone(string $context): string
    {
        $scores = [];

        foreach ($this->toneProfiles as $tone => $profile) {
            if ($tone === 'neutral') {
                continue;
            }

            $score = 0;
            foreach ($profile['keywords'] as $keyword) {
                if (str_contains($context, $keyword)) {
                    $score++;
                }
            }
            $scores[$tone] = $score;
        }

        // Find the highest scoring tone
        arsort($scores);
        $bestTone = array_key_first($scores);
        $bestScore = $scores[$bestTone] ?? 0;

        // If no keywords matched at all, fall back to neutral
        return $bestScore > 0 ? $bestTone : 'neutral';
    }

    /**
     * Get the tone profile (voice, speed) for a given tone name.
     */
    public function getToneProfile(string $tone): array
    {
        return $this->toneProfiles[$tone] ?? $this->toneProfiles['neutral'];
    }
}
