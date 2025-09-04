<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin_site.index');
    }

    public function getAnalyticsData(Request $request)
    {
        try {
            // Déterminer la période (7j / 30j / 12m)
            $periodParam = $request->get('period', '30d');
            $period = match ($periodParam) {
                '7d' => Period::days(7),
                '12m' => Period::months(12),
                default => Period::days(30),
            };

            // Totaux (utilisateurs actifs, sessions, pages vues)
            $totalsCollection = Analytics::get($period, ['activeUsers', 'sessions', 'screenPageViews']);
            $totalsData = $totalsCollection->first() ?? [];

            // Utilisateurs par jour pour graphique
            $usersByDate = Analytics::get($period, ['totalUsers'], ['date']);

            // Pages les plus vues (on récupère le path)
            $mostVisitedPages = Analytics::get($period, ['screenPageViews'], ['pagePath'], 10);

            // Utilisateurs en "temps réel" — ATTENTION : Period en 1er argument
            // Ici on prend la dernière journée (tu peux ajuster si nécessaire)
            $realtime = Analytics::getRealtime(Period::days(1), ['activeUsers']);
            $activeUsersRealtime = $realtime->first()['activeUsers'] ?? 0;

            return response()->json([
                'totals' => [
                    'activeUsers' => $totalsData['activeUsers'] ?? 0,
                    'sessions' => $totalsData['sessions'] ?? 0,
                    'screenPageViews' => $totalsData['screenPageViews'] ?? 0,
                    'activeUsersRealtime' => $activeUsersRealtime,
                ],
                'usersByDate' => $usersByDate->map(fn($row) => [
                    'date' => Carbon::parse($row['date'])->format('d/m'),
                    'totalUsers' => $row['totalUsers'] ?? 0,
                ]),
                'mostVisitedPages' => $mostVisitedPages->map(fn($row) => [
                    'pagePath' => $row['pagePath'] ?? '/',
                    'views' => $row['screenPageViews'] ?? 0,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur API Google Analytics: ' . $e->getMessage());
            return response()->json([
                'error' => 'Impossible de récupérer les données Google Analytics. Vérifiez les logs.'
            ], 500);
        }
    }
}
