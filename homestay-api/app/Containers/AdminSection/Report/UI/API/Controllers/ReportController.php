<?php

namespace App\Containers\AdminSection\Report\UI\API\Controllers;

use App\Containers\AdminSection\Report\Actions\RevenueReportAction;
use App\Containers\AdminSection\Report\Actions\RevenueReportV2Action;
use App\Containers\AdminSection\Report\UI\API\Requests\RevenueReportRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ReportController extends ApiController
{
    public function revenueReport(RevenueReportRequest $request, RevenueReportAction $action)
    {
        return $action->run($request->validated());
    }

    public function reportV2(RevenueReportRequest $request, RevenueReportV2Action $action)
    {
        return $action->run($request->validated());
    }
}
