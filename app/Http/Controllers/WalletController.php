<?php

namespace App\Http\Controllers;

use App\Models\Month;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function edit()
    {
        $months = Month::all();
        $wallets = Wallet::with('months')->get();
        $amounts = [];
        foreach ($wallets as $wallet) {
            foreach ($months as $month) {
                $walletAmount = $wallet->months->firstWhere('id', '=', $month->id);
                $amounts[$wallet->id][$month->id] = $walletAmount ? $walletAmount->pivot->amount : 0;
            }
        }

        return response()->json(compact('wallets', 'months', 'amounts'));
    }

    public function update(Request $request)
    {
        $this->doValidate($request);

        foreach ($request->input('amounts') as $walletId => $monthAmount) {
            if ($wallet = Wallet::find($walletId)) {
                $syncArray = [];
                foreach ($monthAmount as $monthId => $amount) {
                    if ($month = Month::find($monthId)) {
                        $syncArray[$monthId] = ['amount' => $amount ?: 0];
                    }
                }
                $wallet->months()->sync($syncArray);
            }
        }

        return response()->json(['success' => true]);
    }

    public function export()
    {
        $fileName = 'cafeteria.csv';
        $wallets = Wallet::all();
        $months = Month::all();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $headersFirstRow = ['Wallet'];
        foreach ($months as $month) {
            $headersFirstRow[] = $month->name;
        }
        $callback = function () use ($wallets, $headersFirstRow) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headersFirstRow);

            foreach ($wallets as $wallet) {
                $row = [];
                $row[] = $wallet->name;
                foreach ($wallet->months as $walletMonthAmount) {
                    $row[] = $walletMonthAmount->pivot->amount;
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function doValidate(Request $request)
    {
        $total = 0;
        $this->validate($request, [
            'amounts.*.*' => ['nullable', 'numeric', 'min:0', 'max:200000']
        ]);

        foreach ($request->input('amounts') as $walletId => $monthAmount) {
            $sum = array_sum($monthAmount);
            if ($sum >  config('project.wallet_limit')) {
                return response()->json(['success' => false, 'message' => 'Egy tárcában max 200k lehet'], 422);
            }
            $total += $sum;
        }
        if ($total >  config('project.total_limit')) {
            return response()->json(['success' => false, 'message' => 'Összesen 400 000 ft osztható el'], 422);
        }

    }
}
