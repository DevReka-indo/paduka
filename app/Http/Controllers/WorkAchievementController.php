<?php

namespace App\Http\Controllers;

use App\Models\WorkAchievement;
use App\Models\WorkIndicator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WorkAchievementController extends Controller
{
    public function edit(Request $request): View
    {
        $validated = $request->validate([
            'type' => [
                'nullable',
                Rule::in(array_keys(
                    WorkIndicator::typeOptions()
                )),
            ],
            'year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],
        ]);

        $selectedType = $validated['type']
            ?? WorkIndicator::TYPE_PROGRAM_KERJA;

        $selectedYear = (int) (
            $validated['year']
            ?? now()->year
        );

        $inputPeriod = WorkIndicator::PERIOD_MONTHLY;

        $indicators = WorkIndicator::query()
            ->where('type', $selectedType)
            ->where('input_period', $inputPeriod)
            ->active()
            ->ordered()
            ->with([
                'achievements' => function ($query) use (
                    $selectedYear
                ) {
                    $query
                        ->where('year', $selectedYear)
                        ->orderBy('period_number');
                },
            ])
            ->get();

        $periodOptions = $this->periodOptions(
            $inputPeriod
        );

        $availableYears = WorkAchievement::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->prepend(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view(
            'work-program-kpi.achievements.edit',
            compact(
                'selectedType',
                'selectedYear',
                'inputPeriod',
                'indicators',
                'periodOptions',
                'availableYears'
            )
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(array_keys(
                    WorkIndicator::typeOptions()
                )),
            ],
            'year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],
            'achievements' => [
                'nullable',
                'array',
            ],
            'achievements.*' => [
                'nullable',
                'array',
            ],
            'achievements.*.*' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999.99',
            ],
            'notes' => [
                'nullable',
                'array',
            ],
            'notes.*' => [
                'nullable',
                'array',
            ],
            'notes.*.*' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $type = $validated['type'];
        $year = (int) $validated['year'];

        $inputPeriod = WorkIndicator::PERIOD_MONTHLY;

        $maximumPeriod = 12;

        $indicators = WorkIndicator::query()
            ->where('type', $type)
            ->where('input_period', $inputPeriod)
            ->active()
            ->get()
            ->keyBy('id');

        $achievementInput = $request->input(
            'achievements',
            []
        );

        $notesInput = $request->input(
            'notes',
            []
        );

        DB::transaction(function () use (
            $indicators,
            $year,
            $maximumPeriod,
            $achievementInput,
            $notesInput
        ) {
            foreach ($indicators as $indicator) {
                for (
                    $period = 1;
                    $period <= $maximumPeriod;
                    $period++
                ) {
                    $percentage = data_get(
                        $achievementInput,
                        "{$indicator->id}.{$period}"
                    );

                    $notes = data_get(
                        $notesInput,
                        "{$indicator->id}.{$period}"
                    );

                    /*
                     * Apabila nilai dikosongkan, hapus capaian
                     * periode tersebut.
                     */
                    if (
                        $percentage === null
                        || $percentage === ''
                    ) {
                        WorkAchievement::query()
                            ->where(
                                'work_indicator_id',
                                $indicator->id
                            )
                            ->where('year', $year)
                            ->where(
                                'period_number',
                                $period
                            )
                            ->delete();

                        continue;
                    }

                    // WorkAchievement::query()->updateOrCreate(
                    //     [
                    //         'work_indicator_id' => $indicator->id,
                    //         'year' => $year,
                    //         'period_number' => $period,
                    //     ],
                    //     [
                    //         'percentage' => $percentage,
                    //         'notes' => filled($notes)
                    //             ? trim((string) $notes)
                    //             : null,
                    //         'created_by' => Auth::id(),
                    //         'updated_by' => Auth::id(),
                    //     ]
                    // );

                    $achievement = WorkAchievement::query()->firstOrNew([
                        'work_indicator_id' => $indicator->id,
                        'year' => $year,
                        'period_number' => $period,
                    ]);

                    if (!$achievement->exists) {
                        $achievement->created_by = Auth::id();
                    }

                    $achievement->percentage = $percentage;
                    $achievement->notes = filled($notes)
                        ? trim((string) $notes)
                        : null;
                    $achievement->updated_by = Auth::id();
                    $achievement->save();
                }
            }
        });

        return redirect()
            ->route('work-program-kpi.achievements.edit', [
                'type' => $type,
                'year' => $year,
            ])
            ->with(
                'success',
                'Data capaian berhasil disimpan.'
            );
    }

    private function periodOptions(
        string $inputPeriod
    ): array {
        if ($inputPeriod === WorkIndicator::PERIOD_QUARTERLY) {
            return [
                1 => 'Januari–Maret',
                2 => 'April–Juni',
                3 => 'Juli–September',
                4 => 'Oktober–Desember',
            ];
        }

        return collect(range(1, 12))
            ->mapWithKeys(fn (int $month) => [
                $month => Carbon::create()
                    ->month($month)
                    ->translatedFormat('F'),
            ])
            ->all();
    }
}
