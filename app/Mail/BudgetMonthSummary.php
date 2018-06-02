<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Repositories\BudgetRepository;

class BudgetMonthSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $budgetMonthSummary;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $budgetMonthSummary)
    {
        $this->user = $user;
        $this->budgetMonthSummary = $budgetMonthSummary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
                    ->view('mail.budget_month_summary')
                    ->subject('homeBudget: Month Budget Raport');
    }
}
