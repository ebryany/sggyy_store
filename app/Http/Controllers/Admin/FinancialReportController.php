<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Financial Report Controller
 * 
 * Controller untuk menampilkan laporan keuangan platform
 */
class FinancialReportController extends Controller
{
    public function __construct(
        private FinancialReportService $financialReportService
    ) {
        $this->middleware('admin');
    }

    /**
     * Display financial report index
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', 'monthly'); // daily, monthly, yearly
        $date = $request->get('date');
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        $report = null;
        $configuration = $this->financialReportService->getConfiguration();

        switch ($period) {
            case 'daily':
                $reportDate = $date ? Carbon::parse($date) : Carbon::today();
                $report = $this->financialReportService->getDailyReport($reportDate);
                break;

            case 'yearly':
                $report = $this->financialReportService->getYearlyReport($year);
                break;

            case 'monthly':
            default:
                $report = $this->financialReportService->getMonthlyReport($year, $month);
                break;
        }

        return view('admin.financial-report.index', compact('report', 'configuration', 'period', 'date', 'year', 'month'));
    }
}

