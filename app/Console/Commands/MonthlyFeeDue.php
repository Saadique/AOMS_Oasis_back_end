<?php

namespace App\Console\Commands;

use App\Services\ServiceGateway;
use Illuminate\Console\Command;

class MonthlyFeeDue extends Command
{

    protected $signature = 'due_date:check';


    protected $description = 'This will check whether the due date is passed for students monthly fee';

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        parent::__construct();
        $this->serviceGateway = $serviceGateway;
    }

    public function handle()
    {
        $this->serviceGateway->studentPaymentsService->changeStatusInDue();
    }
}
