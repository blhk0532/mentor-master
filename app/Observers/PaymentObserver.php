<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\IncomeStatus;
use App\Models\Payment;
use App\Services\IncomeStatusService;
use Exception;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        $income = \App\Models\Income::find($payment->income_id);
        if ($income && ($income->remaining <= 0 || $income->status === IncomeStatus::COMPLETED->value)) {
            throw new Exception('Cannot add payment to a completed income');
        }
    }

    public function created(Payment $payment): void
    {
        IncomeStatusService::recalculateIncomeStatusFor($payment->income_id);
        if (isset($payment->next_payment)) {
            $payment->income->update([
                'next_payment' => $payment->next_payment,
            ]);
        }
    }

    public function updated(Payment $payment): void
    {
        $currentIncomeId = $payment->income_id;

        if ($currentIncomeId) {
            IncomeStatusService::recalculateIncomeStatusFor($currentIncomeId);
        }
        if (isset($payment->next_payment)) {
            $payment->income->update([
                'next_payment' => $payment->next_payment,
            ]);
        }
    }

    public function deleted(Payment $payment): void
    {
        IncomeStatusService::recalculateIncomeStatusFor($payment->income_id);
    }

    public function restored(Payment $payment): void
    {
        IncomeStatusService::recalculateIncomeStatusFor($payment->income_id);
    }
}
