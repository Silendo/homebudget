<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

use App\User;

class BudgetSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $budgetSummary;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $budgetSummary, User $user)
    {
        $this->budgetSummary = $budgetSummary;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.budget_summary')
                    ->subject('homeBudget: Budget Raport')
                    ->with(['now' => Carbon::now() -> format('Y-m-d H:i:s')]);
    }
}
