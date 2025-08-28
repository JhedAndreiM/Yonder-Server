<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaqQuestion;
use App\Models\FaqCategory;
use App\Services\OpenAIService;
use League\CommonMark\CommonMarkConverter;

class FaqController extends Controller
{

    public function showFAQ(){
        $categories = FaqCategory::with('faqs')->get();
        return view('FAQs', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        FaqQuestion::create($request->all());

        return redirect()->back()->with('success', 'FAQ added successfully!');
    }

    public function destroy(FaqQuestion $faqQuestion)
    {
        $faqQuestion->delete();
        return redirect()->back()->with('success', 'FAQ deleted successfully!');
    }
    public function update(Request $request, FaqQuestion $faqQuestion)
    {
        $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faqQuestion->update($request->all());

        return redirect()->back()->with('success', 'FAQ updated successfully!');
    }
    protected $ai;

    public function __construct(OpenAIService $ai)
    {
        $this->ai = $ai;
    }

public function search(Request $request)
{
    $query = $request->input('q');
    if (!$query) {
        return response()->json([
            'answer' => 'Please enter a question to search.',
        ]);
    }
    $result = $this->ai->askFAQ($query);
    $answer = $result['answer'];
    $faqId = $result['faq_id'];
    $converter = new CommonMarkConverter();
    $answerHtml = (string) $converter->convert($answer);
    $categoryId = null;
    if ($faqId) {
        $faq = FaqQuestion::with('category')->find($faqId);
        if ($faq) {
            $categoryId = $faq->category->id;
        }
    }
    return response()->json([
        'answer' => $answerHtml,
        'faq_id' => $faqId,
        'category_id' => $categoryId,
    ]);
}


}
