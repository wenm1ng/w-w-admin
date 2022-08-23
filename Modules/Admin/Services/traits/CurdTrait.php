<?php

namespace Modules\Admin\Services\traits;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Modules\Common\Exceptions\CodeData;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;
use Modules\Common\Services\BaseService;

/**
 * 增删改查
 *
 * @method BaseService apiError(string $message = MessageData::API_ERROR_EXCEPTION, int $status = StatusData::BAD_REQUEST)
 * @property  Model $model
 * @property  bool $dataLimit
 */
trait CurdTrait
{
    protected bool $dataLimit = false;

    /**
     * @param array $data
     * @param callable|null $condition
     * @return JsonResponse
     */
    public function index(array $data, callable $condition = null)
    {
        $query = $this->model::query();

        if ($this->dataLimit) {
            $query->where('admin_id', request()->user()->getKey());
        }

        // TODO condition
        if ($condition && is_callable($condition)) {
            $query = $condition($query);
        }

        $list = $query->orderBy('id', 'desc')
            ->paginate($data['limit'] ?? 10);

        return $this->apiSuccess('', [
            'list' => $list->items(),
            'total' => $list->total()
        ]);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function store(array $data)
    {
        return $this->commonCreate($this->model, array_merge($data, ['admin_id' => request()->user()->getKey()]));
    }

    /**
     * @param int $id
     * @param array $data
     * @return JsonResponse|void
     */
    public function update(int $id, array $data)
    {
        return $this->commonUpdate($this->model, $id, $data);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $query = $this->model::query();
        if ($this->dataLimit) {
            $query->where('admin_id', request()->user()->getKey());
        }
        $row = $query->where($this->model->getKeyName(), $id)->first();
        return $this->apiSuccess('', $row);
    }

    /**
     * @param int $id
     * @return JsonResponse|BaseService
     */
    public function destroy(int $id)
    {
        if ($this->model::destroy($id)) {
            return $this->apiSuccess(MessageData::DELETE_API_SUCCESS);
        }

        return $this->apiError(MessageData::DELETE_API_ERROR);
    }

    /**
     * @param string $message
     * @param null $data
     * @param int $status
     * @return JsonResponse
     */
    public function apiSuccess(string $message = '', $data = null, int $status = StatusData::Ok): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], CodeData::OK);
    }

    /**
     * @param $model
     * @param array $data
     * @param string $successMessage
     * @param string $errorMessage
     * @return JsonResponse|void
     */
    public function commonCreate($model, array $data = [], string $successMessage = MessageData::ADD_API_SUCCESS, string $errorMessage = MessageData::ADD_API_ERROR)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $model->fill($data);
        if ($model->save()) {
            return self::apiSuccess($successMessage);
        }
        $this->apiError($errorMessage);
    }

    /**
     * @param Model $model
     * @param int $id
     * @param array $data
     * @param string $successMessage
     * @param string $errorMessage
     * @return JsonResponse|void
     */
    public function commonUpdate($model, $id, array $data = [], string $successMessage = MessageData::UPDATE_API_SUCCESS, string $errorMessage = MessageData::UPDATE_API_ERROR)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        /** @var Model $row */
        $row = $model->find($id);
        if ($row && $row->fill($data)->save()) {
            return $this->apiSuccess($successMessage);
        }
        $this->apiError($errorMessage);
    }
}
