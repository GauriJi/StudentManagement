<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AccountantController extends Controller
{
    /* ===================== 1. Dashboard ===================== */
    public function dashboard()
    {
        $today = date('Y-m-d');

        $d['total_collected']    = PaymentRecord::sum('amt_paid') ?? 0;
        $d['today_collected']    = PaymentRecord::whereDate('created_at', $today)->sum('amt_paid') ?? 0;
        $d['month_collected']    = PaymentRecord::whereYear('created_at', date('Y'))
                                                ->whereMonth('created_at', date('m'))->sum('amt_paid') ?? 0;
        $d['total_fees_defined'] = Payment::sum('amount') ?? 0;
        $d['pending_fees']       = max(0, $d['total_fees_defined'] - $d['total_collected']);
        $d['recent_payments']    = PaymentRecord::with(['payment', 'student'])->latest()->take(10)->get();

        // Safe expense queries — work even if migration not run yet
        try {
            $d['total_expenses']  = Schema::hasTable('expenses') ? Expense::whereYear('expense_date', date('Y'))->sum('amount') : 0;
            $d['recent_expenses'] = Schema::hasTable('expenses') ? Expense::latest()->take(5)->get() : collect();
        } catch (\Exception $e) {
            $d['total_expenses']  = 0;
            $d['recent_expenses'] = collect();
        }

        return view('pages.accountant.dashboard', $d);
    }

    /* ===================== 2. Fee Collection ===================== */
    public function feeCollection()
    {
        $d['classes']  = MyClass::orderBy('name')->get();
        $d['payments'] = Payment::with('my_class')->orderBy('year', 'desc')->get();

        // Attach paid totals
        $d['payments']->each(function ($p) {
            $p->paid_count      = PaymentRecord::where('payment_id', $p->id)->count();
            $p->total_collected = PaymentRecord::where('payment_id', $p->id)->sum('amt_paid');
            $p->total_balance   = PaymentRecord::where('payment_id', $p->id)->sum('balance');
        });

        $d['grand_collected'] = PaymentRecord::sum('amt_paid');
        $d['grand_balance']   = PaymentRecord::sum('balance');

        return view('pages.accountant.fee_collection', $d);
    }

    /* ===================== 3. Student Fees ===================== */
    public function studentFees(Request $request)
    {
        $d['classes']       = MyClass::orderBy('name')->get();
        $d['selected_class'] = $request->class_id;

        $query = PaymentRecord::with(['payment.my_class', 'student'])
            ->orderByDesc('created_at');

        if ($request->filled('class_id')) {
            $query->whereHas('payment', fn($q) => $q->where('my_class_id', $request->class_id));
        }
        if ($request->filled('student_name')) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', '%'.$request->student_name.'%'));
        }

        $d['records'] = $query->paginate(20);
        return view('pages.accountant.student_fees', $d);
    }

    /* ===================== 4. Expenses ===================== */
    public function expenses(Request $request)
    {
        $d['categories'] = ['general', 'salary', 'maintenance', 'utilities', 'supplies', 'other'];
        $hasTable = Schema::hasTable('expenses');

        if ($hasTable) {
            $d['expenses'] = Expense::when($request->category, fn($q, $c) => $q->where('category', $c))
                ->when($request->month, fn($q, $m) => $q->whereRaw("DATE_FORMAT(expense_date,'%Y-%m') = ?", [$m]))
                ->latest('expense_date')->paginate(20);
            $d['total_this_month'] = Expense::whereRaw("DATE_FORMAT(expense_date,'%Y-%m') = ?", [date('Y-m')])->sum('amount');
            $d['total_this_year']  = Expense::whereYear('expense_date', date('Y'))->sum('amount');
        } else {
            $d['expenses']         = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $d['total_this_month'] = 0;
            $d['total_this_year']  = 0;
        }

        return view('pages.accountant.expenses', $d);
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'category'     => 'required',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'title'        => $request->title,
            'category'     => $request->category,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'description'  => $request->description,
            'ref_no'       => $request->ref_no,
            'recorded_by'  => Auth::id(),
        ]);

        return back()->with('flash_success', 'Expense recorded successfully.');
    }

    public function destroyExpense($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('flash_success', 'Expense deleted.');
    }

    /* ===================== 5. Reports ===================== */
    public function reports(Request $request)
    {
        $year     = $request->year ?? date('Y');
        $month    = $request->month;
        $hasTable = Schema::hasTable('expenses');

        $feeQuery = PaymentRecord::query()->whereYear('created_at', $year);
        if ($month) $feeQuery->whereMonth('created_at', $month);

        $d['year']         = $year;
        $d['month']        = $month;
        $d['years']        = range(date('Y'), date('Y') - 5);
        $d['total_income'] = $feeQuery->sum('amt_paid');

        if ($hasTable) {
            $expQuery = Expense::query()->whereYear('expense_date', $year);
            if ($month) $expQuery->whereMonth('expense_date', $month);
            $d['total_expense'] = $expQuery->sum('amount');
        } else {
            $d['total_expense'] = 0;
        }
        $d['net_balance'] = $d['total_income'] - $d['total_expense'];

        $d['monthly'] = collect(range(1, 12))->map(function ($m) use ($year, $hasTable) {
            return [
                'month'   => date('M', mktime(0, 0, 0, $m, 1)),
                'income'  => PaymentRecord::whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('amt_paid'),
                'expense' => $hasTable ? Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('amount') : 0,
            ];
        });

        $d['by_class'] = Payment::with('my_class')->get()->map(function ($p) {
            $p->collected = PaymentRecord::where('payment_id', $p->id)->sum('amt_paid');
            return $p;
        })->groupBy('my_class.name');

        return view('pages.accountant.reports', $d);
    }

    /* ===================== 6. Receipts ===================== */
    public function receipts(Request $request)
    {
        $d['records'] = PaymentRecord::with(['payment', 'student', 'receipt'])
            ->when($request->student_name, fn($q, $n) => $q->whereHas('student', fn($s) => $s->where('name', 'like', "%$n%")))
            ->latest()
            ->paginate(20);
        return view('pages.accountant.receipts', $d);
    }
}
