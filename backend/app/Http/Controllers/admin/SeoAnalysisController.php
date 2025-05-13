<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seo\Services\SeoAnalyzer;
use App\Seo\Rules\KeywordInTitleRule;
// Thêm các rule khác ở đây

class SeoAnalysisController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'focus_keyword' => 'required',
        ]);

        $rules = [
            new KeywordInTitleRule(),
            
        ];

        $analyzer = new SeoAnalyzer($rules);

        $result = $analyzer->analyze(
            $request->input('title'),
            $request->input('content'),
            $request->input('focus_keyword')
        );

        return response()->json($result);
    }
}
