<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
//use App\Containers\SharedSection\Room\Actions\CreateHostAction;
//use App\Containers\SharedSection\Room\Actions\DeleteHostAction;
//use App\Containers\SharedSection\Room\Actions\FindHostByIdAction;
//use App\Containers\SharedSection\Room\Actions\ListHostsAction;
//use App\Containers\SharedSection\Room\Actions\UpdateHostAction;
//use App\Containers\SharedSection\Room\UI\API\Requests\CreateHostRequest;
//use App\Containers\SharedSection\Room\UI\API\Requests\DeleteHostRequest;
//use App\Containers\SharedSection\Room\UI\API\Requests\FindHostByIdRequest;
//use App\Containers\SharedSection\Room\UI\API\Requests\ImportHostsRequest;
//use App\Containers\SharedSection\Room\UI\API\Requests\ListHostsRequest;
//use App\Containers\SharedSection\Room\UI\API\Requests\UpdateHostRequest;
//use App\Containers\SharedSection\Room\UI\API\Transformers\HostTransformer;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportHostsRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ImportHostController extends ApiController
{
//    public function create(CreateHostRequest $request, CreateHostAction $action): JsonResponse
//    {
//       $host = $action->run($request);
//
//       return Response::create($host, HostTransformer::class)->created();
//    }
//
//    public function findById(FindHostByIdRequest $request, FindHostByIdAction $action): JsonResponse
//    {
//        $host = $action->run($request);
//
//        return Response::create($host, HostTransformer::class)->ok();
//    }
//
//    public function list(ListHostsRequest $request, ListHostsAction $action): JsonResponse
//    {
//        $hosts = $action->run($request);
//
//        return Response::create($hosts, HostTransformer::class)->ok();
//    }
//
//    public function update(UpdateHostRequest $request, UpdateHostAction $action): JsonResponse
//    {
//        $host = $action->run($request);
//
//        return Response::create($host, HostTransformer::class)->ok();
//    }
//
//    public function delete(DeleteHostRequest $request, DeleteHostAction $action): JsonResponse
//    {
//        $action->run($request);
//
//        return Response::noContent();
//    }

    public function __invoke(ImportHostsRequest $request)
    {
        $result = app(\App\Containers\SharedSection\Room\Actions\ImportHostsAction::class)->run($request);
        return response()->json(['message' => $result]);
    }
}
