<?php

namespace App\Containers\AdminSection\Host\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Host\Actions\CreateHostAction;
use App\Containers\AdminSection\Host\Actions\DetailHostAction;
use App\Containers\AdminSection\Host\Actions\GetHostAction;
use App\Containers\AdminSection\Host\Actions\ListingRoomByHostAction;
use App\Containers\AdminSection\Host\Actions\UpdateActiveHostAction;
use App\Containers\AdminSection\Host\Actions\UpdateBlockHostAction;
use App\Containers\AdminSection\Host\Actions\UpdateHostAction;
use App\Containers\AdminSection\Host\UI\API\Requests\CreateHostRequest;
use App\Containers\AdminSection\Host\UI\API\Requests\GetHostRequest;
use App\Containers\AdminSection\Host\UI\API\Requests\ListingRoomByHostRequest;
use App\Containers\AdminSection\Host\UI\API\Requests\UpdateHostRequest;
use App\Containers\AdminSection\Host\UI\API\Transformers\HostTransformer;
use App\Containers\AdminSection\Host\UI\API\Transformers\RoomTransformer;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class HostController extends ApiController
{
    public function getHost(GetHostRequest $request, GetHostAction $action)
    {
        $hosts = $action->run($request);
        return Response::create($hosts, HostTransformer::class)->ok();
    }

    public function getListingByHost(ListingRoomByHostRequest $request, ListingRoomByHostAction $action)
    {
        $hostId = $request->id;
        $pageSize = $request['page_size'] ?? 10;
        $listings = $action->run($hostId, $pageSize);
        return Response::create($listings, RoomTransformer::class)->ok();
    }
    public function updateBlockStatus(Request $request, UpdateBlockHostAction $action)
    {
        $hostId = $request->id;
        $result = $action->run($hostId);
        return response()->json(
            [
                'is_blocked' => $result,
            ]
        );
    }
    public function updateActive(Request $request, UpdateActiveHostAction $action)
    {
        $hostId = $request->id;
        $result = $action->run($hostId);
        return response()->json(
            [
                'is_active' => $result,
            ]
        );
    }

    public function updateHost(UpdateHostRequest $request, UpdateHostAction $action)
    {
        $hostId = $request->id;
        $host = $action->run($hostId, $request->validated());
        return response()->json([
            'message' => 'Host updated successfully',
            'data' => $host
        ], 200);
    }

    public function detailHost(Request $request, DetailHostAction $action)
    {
        $hostId = $request->id;
        $host = $action->run($hostId);
        return response()->json([
            'data' => [
                'id' => $host->id,
                'brand_name' => $host->business_name,
                'description' => $host->description,
                'address' => $host->address,
                'data' => $host->data,
                'avatar' => S3Helper::getS3ImageUrl($host->avatar),
                'first_name' => $host->user->first_name,
                'last_name' => $host->user->last_name,
                'email' => $host->user->email,
                'phone_number' => $host->user->phone_number,
                'is_active' => $host->is_active,
            ]
        ], 200);
    }

    public function create(CreateHostRequest $request, CreateHostAction $action)
    {
        $host = $action->run($request->validated());
        return response()->json([
            'message' => 'Host created successfully',
            'data' => $host
        ], 201);
    }
}
