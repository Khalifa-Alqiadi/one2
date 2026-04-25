<?php

namespace App\Http\Controllers;

use App\Services\SportmonksService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FixturesController extends Controller
{
    public function __construct(protected SportmonksService $sm) {}

    /**
     * الصفحة الرئيسية — مباريات الأسبوع الماضي افتراضياً.
     * تقبل ?from=YYYY-MM-DD&to=YYYY-MM-DD لتخصيص المدى.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('ar');

        $to   = $request->input('to',   Carbon::yesterday()->toDateString());
        $from = $request->input('from', Carbon::yesterday()->subDays(6)->toDateString());

        $raw = $this->sm->fixturesBetween($from, $to);

        // تطبيع
        $fixtures = array_map([SportmonksService::class, 'normalize'], $raw);


        // تجميع بالتاريخ للعرض
        $grouped = [];
        foreach ($fixtures as $fx) {
            $grouped[$fx['date']][] = $fx;
        }
        krsort($grouped);

        // قوائم فلترة فريدة
        $leagues = collect($fixtures)
            ->pluck('league')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $states = collect($fixtures)
            ->pluck('state')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('frontEnd.football.fixtures.index', compact(
            'fixtures', 'grouped', 'leagues', 'states', 'from', 'to'
        ));
    }

    /**
     * JSON endpoint للبيانات التفصيلية (تُستهلك بالـ JS بدون إعادة تحميل).
     */
    public function show(int $id)
    {
        $data = $this->sm->fixture($id);

        if (!$data) {
            return response()->json(['error' => 'Fixture not found'], 404);
        }

        return response()->json([
            'fixture' => SportmonksService::normalize($data),
        ]);
    }
}
