<?php

namespace App\Containers\AdminSection\Dashboard\UI\API\Controllers;

use App\Containers\AdminSection\Dashboard\Actions\ChartAction;
use App\Containers\AdminSection\Dashboard\Actions\IndexAction;
use App\Containers\AdminSection\Dashboard\Actions\TopHostAction;
use App\Containers\AdminSection\Dashboard\Actions\TopRoomByOrderAction;
use App\Containers\AdminSection\Dashboard\Actions\TopRoomsAction;
use App\Containers\AdminSection\Dashboard\Actions\TopUserAction;
use App\Containers\AdminSection\Dashboard\UI\API\Requests\DashboardRequest;
use App\Ship\Parents\Controllers\ApiController;

final class DashboardController extends ApiController
{
    public function index(DashboardRequest $request, IndexAction $action)
    {

        $data = $action->run($request->validated());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function chart(DashboardRequest $request, ChartAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function topHost(DashboardRequest $request, TopHostAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function topRoom(DashboardRequest $request, TopRoomsAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function topUser(DashboardRequest $request,  TopUserAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function topRoomByOrder(DashboardRequest $request, TopRoomByOrderAction $action)
    {
        $data = $action->run($request->validated());
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

}
