<?php

namespace App\Repositories;

use App\Models\BoardRegistationProcess;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create($data)
    {
        $payment = new Payment();
        $payment->depositor_name = @$data['depositor_name'];
        $payment->depositor_mobile = @$data['depositor_mobile'];
        $payment->no_of_students = @$data['no_of_students'];
        $payment->amount = @$data['amount'];
        $payment->eiin = @$data['eiin'];
        $payment->class = @$data['class'];
        $payment->session_year = date('Y');
        $payment->session_token = @$data['session_token'];
        $payment->invoice_no = @$data['invoice_no'];
        $payment->invoice_date = date('Y-m-d');
        $payment->save();

        return $payment;
    }

    public function boardRegistrationProcessCreate($data)
    {
        $boardReg = new BoardRegistationProcess();
        $boardReg->payment_uid = @$data['uid'];
        $boardReg->eiin = @$data['eiin'];
        $boardReg->class = @$data['class'];
        $boardReg->no_of_payment_students = @$data['no_of_students'];
        $boardReg->no_of_temp_students = 0;
        $boardReg->no_of_registered_students = 0;
        $boardReg->session_year = date('Y');
        $boardReg->save();

        return $boardReg;
    }

    public function isExists($eiin, $class, $year)
    {
        return Payment::where('eiin', $eiin)->where('class', $class)->where('session_year', $year)->whereNotNull('transaction_id')->first();
    }

    public function remainingStudent($eiin, $class, $year, $fromWhere = 0)
    {
        // dd($fromWhere);
        $no_of_payment_students = BoardRegistationProcess::where('eiin', $eiin)->where('class', $class)->where('session_year', $year)->sum('no_of_payment_students');
        $no_of_temp_students = BoardRegistationProcess::where('eiin', $eiin)->where('class', $class)->where('session_year', $year)->sum('no_of_temp_students');
        $no_of_registered_students = BoardRegistationProcess::where('eiin', $eiin)->where('class', $class)->where('session_year', $year)->sum('no_of_registered_students');
        if ($fromWhere == 0)
            return ($no_of_payment_students - $no_of_registered_students);
        if ($fromWhere == 1)
            // dd($no_of_payment_students);
            return ($no_of_payment_students - $no_of_registered_students - $no_of_temp_students);
    }
    public function remainingStudentExists($eiin, $class, $year)
    {
        return BoardRegistationProcess::where('eiin', $eiin)->where('class', $class)->where('session_year', $year)->first();
    }
}
