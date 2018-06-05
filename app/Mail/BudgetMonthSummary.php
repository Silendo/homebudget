<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

use App\User;
use App\Repositories\BudgetRepository;

class BudgetMonthSummary extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $budgetMonthSummary;
    public $budgetMonthDetails;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $budgetMonthSummary, array $budgetMonthDetails, User $user)
    {
        $this->user = $user;
        $this->budgetMonthDetails = $budgetMonthDetails;
        $this->budgetMonthSummary = $budgetMonthSummary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDF::loadView('mail.budget_month_summary_attachment', array( 'details' => $this->budgetMonthDetails));
        return $this->to($this->user->email)
                    ->view('mail.budget_month_summary')
                    ->attachData($pdf->output(), 'monthBudgetSummary_'.date('Y-m-d').'.pdf', [ 'mime' => 'application/pdf', ])
                    ->subject('homeBudget: Month Budget Raport');
    }
}
