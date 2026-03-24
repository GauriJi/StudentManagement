<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;

class FeeManagementController extends Controller
{
    public function index(Request $request)
    {
        $d['classes']     = MyClass::orderBy('id')->get();
        $d['years']       = $this->getYears();
        $d['selected_class'] = $request->class_id;
        $d['selected_year']  = $request->year ?? date('Y');

        $query = Payment::with('my_class');

        if ($request->filled('class_id')) {
            $query->where('my_class_id', $request->class_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $payments = $query->get();

        // Attach payment records stats per payment
        $payments->each(function ($p) {
            $p->paid_count        = PaymentRecord::where('payment_id', $p->id)->count();
            $p->total_collected   = PaymentRecord::where('payment_id', $p->id)->sum('amt_paid');
        });

        $d['payments']        = $payments;
        $d['total_collected'] = $payments->sum('total_collected');
        $d['grand_total']     = PaymentRecord::sum('amt_paid');

        return view('pages.super_admin.fees.index', $d);
    }

    private function getYears()
    {
        $start = 2020;
        $end   = (int) date('Y') + 1;
        return range($start, $end);
    }
}
