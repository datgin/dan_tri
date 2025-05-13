<?php

namespace App\RankmathSEOForLaravel\Services;

use App\RankmathSEOForLaravel\DTO\SeoAnalysisResult;
use App\RankmathSEOForLaravel\Rules\RuleInterface;

class SeoAnalyzer
{
    protected array $rules = [];

    public function __construct($rules = [])
    {
        $this->rules = $rules;
    }

    public function analyze(string $title, string $content, string $focusKeyword) : SeoAnalysisResult
    {
        $totalScore = 0;
        $check = [];

        foreach ($this->rules as $rule) {
            if(!$rule instanceof RuleInterface) continue;

            $result = $rule->check($title, $content, $focusKeyword);
            $totalScore += $result['score'];
            $checks[] = $result;
        }

        return new SeoAnalysisResult ($totalScore, $checks);
    }
}