<?php

namespace App\RankmathSEOForLaravel\DTO;

class SeoAnalysisResult
{
    public float $score;
    public array $checks;

    public function __construct(float $score, array $checks)
    {
        $this->score = $score;
        $this->checks = $checks;
    }
}